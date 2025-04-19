<?php

namespace App\Domain\Event;

use App\Domain\Model\Task;
use Ramsey\Uuid\Uuid;

class TaskAdded extends DomainEvent
{
    public static function create(Task $task): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            'task_added',
            new \DateTimeImmutable(),
            json_encode($task),
        );
    }
}