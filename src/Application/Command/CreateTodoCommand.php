<?php

namespace App\Application\Command;

class CreateTodoCommand
{
    public function __construct(
        public readonly string $name,
    )
    {
    }
}