<?php

namespace App\Domain\Exception;

class InvalidTodoNameException extends \DomainException
{
    public function __construct(string $name)
    {
        parent::__construct("Name {$name} must be between 1 and 50 characters long.");
    }
}