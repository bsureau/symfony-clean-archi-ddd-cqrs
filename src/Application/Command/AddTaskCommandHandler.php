<?php

namespace App\Application\Command;

use App\Domain\Entity\Task;
use App\Domain\Entity\Todo;
use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Repository\DomainEventRepository;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use Ramsey\Uuid\Uuid;

class AddTaskCommandHandler
{
    public function __construct(
        private readonly TodoRepository        $todoRepository,
        private readonly DomainEventRepository $domainEventRepository
    )
    {
    }

    public function __invoke(AddTaskCommand $command): void
    {
        $todo = $this->getTodoOrFail($command->todoId);

        $task = $this->createTask($command->name);
        $todo->addTask($task);

        $this->todoRepository->save($todo);
        $this->saveDomainEvents($todo->pullDomainEvents());
    }

    private function getTodoOrFail(string $todoId): Todo
    {
        $todo = $this->todoRepository->findById(TodoId::create($todoId));
        if ($todo === null) {
            throw new TodoNotFoundException($todoId);
        }
        return $todo;
    }

    private function createTask(string $name): Task
    {
        return Task::create(TaskId::create(Uuid::uuid4()), $name);
    }

    private function saveDomainEvents(array $domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->domainEventRepository->save($domainEvent);
        }
    }
}