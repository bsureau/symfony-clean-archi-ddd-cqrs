<?php

namespace App\Domain\Exception;

class TaskNotFoundException extends \DomainException
{
    public function __construct(string $taskId)
    {
        parent::__construct(sprintf('Task with ID %s not found.', $taskId));
    }
}