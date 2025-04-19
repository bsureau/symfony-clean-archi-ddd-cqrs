<?php

namespace App\Domain\Model;

use App\Domain\Event\TaskAdded;
use App\Domain\Event\TaskDone;
use App\Domain\Event\TodoCreated;
use App\Domain\Exception\InvalidTodoNameException;
use App\Domain\Exception\TaskNotFoundException;
use App\Domain\Exception\TooManyTodoTasksException;
use App\Domain\Snapshot\Snapshotable;
use App\Domain\Snapshot\TodoSnapshot;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;

class Todo extends AggregateRoot implements Snapshotable, \JsonSerializable
{
    private function __construct(
        private TodoId $id,
        private string $name,
        private array  $tasks = []
    )
    {
        parent::__construct();
    }

    public function addTask(Task $task): void
    {
        $numberOfTaskTodo = count(array_filter($this->tasks, function (Task $task) {
            return $task->getStatus() === Task::STATUS_TODO;
        }));

        if ($numberOfTaskTodo >= 5) {
            throw new TooManyTodoTasksException();
        }

        $this->tasks[] = $task;
        $this->events[] = TaskAdded::create($task);
    }


    public function markTaskAsDone(TaskId $taskId): void
    {
        $tasks = array_values(array_filter($this->tasks, function (Task $task) use ($taskId) {
            return $task->getId()->__toString() === $taskId->__toString();
        }));

        if (count($tasks) === 0) {
            throw new TaskNotFoundException($taskId);
        }

        $task = $tasks[0];

        $task->markAsDone();
        $this->events[] = TaskDone::create($task);
    }

    public static function create(TodoId $id, string $name): self
    {
        if (empty($name) || strlen($name) > 50) {
            throw new InvalidTodoNameException($name);
        }

        $todo = new self($id, $name);

        $todo->events[] = TodoCreated::create($todo);

        return $todo;
    }

    public function toSnapshot(): TodoSnapshot
    {
        $tasks = [];
        foreach ($this->tasks as $task) {
            $tasks[] = $task->toSnapshot();
        }

        return new TodoSnapshot(
            $this->id,
            $this->name,
            $tasks
        );
    }

    /**
     * @param TodoSnapshot $snapshot
     * @return static
     */

    public static function fromSnapshot(mixed $snapshot): self
    {
        $tasks = [];
        foreach ($snapshot->getTasks() as $task) {
            $tasks[] = Task::fromSnapshot($task);
        }

        return new self(
            TodoId::create($snapshot->getId()),
            $snapshot->getName(),
            $tasks
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->__toString(),
            'name' => $this->name,
            'tasks' => array_map(function (Task $task) {
                return $task->jsonSerialize();
            }, $this->tasks)
        ];
    }
}