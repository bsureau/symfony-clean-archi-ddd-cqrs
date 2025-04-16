<?php

namespace App\Domain\Exception;

class TodoNotFoundException extends \DomainException
{
    public function __construct(string $todoId)
    {
        parent::__construct(sprintf('Todo with ID %s not found.', $todoId));
    }
}