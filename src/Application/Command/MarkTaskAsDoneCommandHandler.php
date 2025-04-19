<?php

namespace App\Application\Command;

use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Model\Todo;
use App\Domain\Repository\DomainEventRepository;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;

class MarkTaskAsDoneCommandHandler extends CommandHandler
{
    public function __construct(
        private TodoRepository $todoRepository,
        DomainEventRepository  $domainEventRepository
    )
    {
        parent::__construct($domainEventRepository);
    }

    public function __invoke(MarkTaskAsDoneCommand $command): void
    {
        $todo = $this->getTodoOrFail($command->todoId);
        $this->markTaskAsDone($todo, $command->taskId);
        $this->todoRepository->update($todo);
        $this->saveDomainEvents($todo->pullDomainEvents());
    }

    public function getTodoOrFail(string $todoId): Todo
    {
        $todo = $this->todoRepository->findById(TodoId::create($todoId));
        if ($todo === null) {
            throw new TodoNotFoundException($todoId);
        }
        return $todo;
    }

    public function markTaskAsDone(Todo $todo, string $taskId): void
    {
        $todo->markTaskAsDone(TaskId::create($taskId));
    }
}