<?php

namespace App\Infrastructure\Database\InMemory\Repository;

use App\Domain\Event\DomainEvent;
use App\Domain\Repository\DomainEventRepository;

class InMemoryDomainEventRepository implements DomainEventRepository
{

    /**
     * @var DomainEvent[]
     */
    public array $events = [];


    public function save(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}