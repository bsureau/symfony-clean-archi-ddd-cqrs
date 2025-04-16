<?php

namespace App\Domain\Entity;

use App\Domain\VO\TaskId;

class Task
{

    const STATUS_TODO = 'TODO';
    const STATUS_DONE = 'DONE';

    private string $status;
    private Todo $todo;

    private function __construct(private TaskId $id, private string $name)
    {
        $this->status = self::STATUS_TODO;
    }

    public function getId(): TaskId
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTodo(): Todo
    {
        return $this->todo;
    }

    public function setTodo(Todo $todo): void
    {
        $this->todo = $todo;
    }

    public function markAsDone(): void
    {
        $this->status = self::STATUS_DONE;
    }

    public static function create(TaskId $taskId, string $name): self
    {
        return new self($taskId, $name);
    }
}