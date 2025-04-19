<?php

namespace App\Domain\Model;

use App\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    protected array $events;

    protected function __construct()
    {
        $this->events = [];
    }

    public function pullDomainEvents(): array
    {
        return $this->events;
    }
}