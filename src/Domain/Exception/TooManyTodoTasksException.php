<?php

namespace App\Domain\Exception;

class TooManyTodoTasksException extends \DomainException
{
    public function __construct()
    {
        parent::__construct("You can't have more than 5 tasks in TODO.");
    }
}