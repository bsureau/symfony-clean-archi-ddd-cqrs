<?php

namespace App\Domain\Snapshot;

class TaskSnapshot
{

    public function __construct(
        private string $id,
        private string $name,
        private string $status,
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


    public function getStatus(): string
    {
        return $this->status;
    }
}