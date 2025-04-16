<?php

namespace App\Domain\Entity;

use App\Domain\Event\TaskAdded;
use App\Domain\Event\TodoCreated;
use App\Domain\Exception\InvalidTodoNameException;
use App\Domain\Exception\TaskNotFoundException;
use App\Domain\Exception\TooManyTodoTasksException;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Todo extends AggregateRoot
{
    /**
     * The Collection interface and ArrayCollection class, like everything else in the Doctrine namespace, are neither part of the ORM,
     * nor the DBAL, it is a plain PHP class that has no outside dependencies apart from dependencies on PHP itself (and the SPL).
     * Therefore, using this class inside our domain layer does not introduce a coupling to the ORM.
     * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html#collections
     */
    private Collection $tasks;

    private function __construct(
        private TodoId $id,
        private string $name,
    )
    {
        parent::__construct();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): TodoId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addTask(Task $task): void
    {
        $numberOfTaskTodo = $this->tasks->filter(function (Task $task) {
            return $task->getStatus() === Task::STATUS_TODO;
        })->count();

        if ($numberOfTaskTodo >= 5) {
            throw new TooManyTodoTasksException();
        }

        $this->tasks[] = $task;
        $task->setTodo($this);

        $this->events[] = TaskAdded::create($task);
    }

    public function getTasks(): array
    {
        return $this->tasks->toArray();
    }

    public function markTaskAsDone(TaskId $taskId): void
    {
        $task = $this->tasks->filter(function (Task $task) use ($taskId) {
            return $task->getId()->__toString() === $taskId->__toString();
        })->first();

        if ($task === false) {
            throw new TaskNotFoundException($taskId);
        }

        $task->markAsDone();
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
}