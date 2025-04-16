<?php

namespace App\Application\Command;

class MarkTaskAsDoneCommand
{
    public function __construct(public string $todoId, public readonly string $taskId)
    {
    }
}