<?php

namespace App\Domain\Model;

use App\Domain\Snapshot\Snapshotable;
use App\Domain\Snapshot\TaskSnapshot;
use App\Domain\VO\TaskId;

class Task implements Snapshotable, \JsonSerializable
{

    const STATUS_TODO = 'TODO';
    const STATUS_DONE = 'DONE';

    private function __construct(private TaskId $id, private string $name, private string $status)
    {
    }

    public function getId(): TaskId
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function markAsDone(): void
    {
        $this->status = self::STATUS_DONE;
    }

    public static function create(TaskId $taskId, string $name): self
    {
        return new self($taskId, $name, self::STATUS_TODO);
    }
    
    public function toSnapshot(): TaskSnapshot
    {
        return new TaskSnapshot(
            $this->id,
            $this->name,
            $this->status
        );
    }

    /**
     * @param TaskSnapshot $snapshot
     */
    public static function fromSnapshot(mixed $snapshot): self
    {
        return new self(
            TaskId::create($snapshot->getId()),
            $snapshot->getName(),
            $snapshot->getStatus()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->__toString(),
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}