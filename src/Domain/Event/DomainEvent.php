<?php

namespace App\Domain\Event;

abstract class DomainEvent
{
    protected function __construct(
        private readonly string             $id,
        private readonly string             $type,
        private readonly \DateTimeImmutable $occurredOn,
        private readonly string             $payload
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }
}