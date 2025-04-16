<?php

namespace App\Application\Command;

class AddTaskCommand
{
    public function __construct(
        public readonly string $todoId,
        public readonly string $name,
    )
    {
    }
}