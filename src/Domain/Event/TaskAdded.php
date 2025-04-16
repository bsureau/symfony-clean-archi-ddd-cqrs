<?php

namespace App\Domain\Event;

use App\Domain\Entity\Task;
use Ramsey\Uuid\Uuid;

class TaskAdded extends DomainEvent
{
    public static function create(Task $task): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            'task_added',
            new \DateTimeImmutable(),
            [
                'taskId' => $task->getId(),
                'name' => $task->getName(),
                'todoId' => $task->getTodo()->getId(),
            ]
        );
    }
}