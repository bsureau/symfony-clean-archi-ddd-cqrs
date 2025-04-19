<?php

namespace App\Application\Command;

use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Model\Task;
use App\Domain\Model\Todo;
use App\Domain\Repository\DomainEventRepository;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use Ramsey\Uuid\Uuid;

class AddTaskCommandHandler extends CommandHandler
{
    public function __construct(
        private readonly TodoRepository $todoRepository,
        DomainEventRepository           $domainEventRepository
    )
    {
        parent::__construct($domainEventRepository);
    }

    public function __invoke(AddTaskCommand $command): void
    {
        $todo = $this->getTodoOrFail($command->todoId);
        $this->addTask($todo, $command->name);
        $this->todoRepository->update($todo);
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

    public function addTask(Todo $todo, string $name): void
    {
        $task = Task::create(TaskId::create(Uuid::uuid4()), $name);
        $todo->addTask($task);
    }
}