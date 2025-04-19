<?php

namespace App\Infrastructure\Database\InMemory\Repository;

use App\Domain\Model\Todo;
use App\Domain\Repository\TodoRepository;
use App\Domain\Snapshot\TodoSnapshot;
use App\Domain\VO\TodoId;

class InMemoryTodoRepository implements TodoRepository
{
    /**
     * @var TodoSnapshot[]
     */
    public $todos = [];

    public function add(Todo $todo): void
    {
        $todoSnapshot = $todo->toSnapshot();
        $this->todos[] = $todoSnapshot;
    }

    public function update(Todo $todo): void
    {
        $todoSnapshot = $todo->toSnapshot();
        $index = array_search($todoSnapshot->getId(), array_map(fn($todoSnapshot) => $todoSnapshot->getId(), $this->todos));

        if ($index !== false) {
            $this->todos[$index] = $todoSnapshot;
        }
    }

    public function findById(TodoId $id): ?Todo
    {
        $todoSnapshot = array_find($this->todos, fn($todoSnapshot) => $todoSnapshot->getId() === $id->__toString());

        return $todoSnapshot ? Todo::fromSnapshot($todoSnapshot) : null;
    }
}