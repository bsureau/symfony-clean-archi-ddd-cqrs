<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Todo;
use App\Domain\VO\TodoId;

interface TodoRepository
{
    public function save(Todo $todo): void;

    public function findById(TodoId $id): ?Todo;
}