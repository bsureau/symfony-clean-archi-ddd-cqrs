<?php

namespace App\Application\Command;

use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;

class MarkTaskAsDoneCommandHandler
{
    public function __construct(private TodoRepository $todoRepository)
    {
    }

    public function __invoke(MarkTaskAsDoneCommand $command): void
    {
        $todoId = TodoId::create($command->todoId);
        $todo = $this->todoRepository->findById($todoId);
        if ($todo === null) {
            throw new TodoNotFoundException($todoId);
        }
        $taskId = TaskId::create($command->taskId);
        $todo->markTaskAsDone($taskId);
        $this->todoRepository->save($todo);
    }
}