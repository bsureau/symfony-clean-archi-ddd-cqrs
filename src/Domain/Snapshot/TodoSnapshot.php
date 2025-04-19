<?php

namespace App\Domain\Snapshot;

class TodoSnapshot
{

    public function __construct(
        private string $id,
        private string $name,
        # @var TaskSnapshot[]|null
        private ?array $tasks
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getTasks(): array
    {
        return $this->tasks;
    }
}