<?php

namespace App\Domain\Repository;

use App\Domain\Model\Todo;
use App\Domain\VO\TodoId;

interface TodoRepository
{
    public function add(Todo $todo): void;

    public function update(Todo $todo): void;

    public function findById(TodoId $id): ?Todo;
}