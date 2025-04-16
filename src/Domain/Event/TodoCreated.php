<?php

namespace App\Domain\Event;

use App\Domain\Entity\Todo;
use Ramsey\Uuid\Uuid;

class TodoCreated extends DomainEvent
{
    public static function create(Todo $todo): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            'todo_created',
            new \DateTimeImmutable(),
            [
                'todoId' => $todo->getId(),
                'name' => $todo->getName(),
            ]
        );
    }
}