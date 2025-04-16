<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Todo;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TodoId;

class InMemoryTodoRepository implements TodoRepository
{
    /**
     * @var Todo[]
     */
    public $todos = [];

    public function save(Todo $todo): void
    {
        $this->todos[] = $todo;
    }

    public function findById(TodoId $id): ?Todo
    {
        foreach ($this->todos as $todo) {
            if ($todo->getId()->__toString() === $id->__toString()) {
                return $todo;
            }
        }
        return null;
    }
}