<?php

namespace App\Domain\Event;

use App\Domain\Model\Task;
use Ramsey\Uuid\Uuid;

class TaskDone extends DomainEvent
{
    public static function create(Task $task): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            'task_done',
            new \DateTimeImmutable(),
            json_encode($task),
        );
    }
}