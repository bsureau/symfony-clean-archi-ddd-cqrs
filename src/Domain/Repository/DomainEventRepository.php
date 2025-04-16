<?php

namespace App\Domain\Repository;

use App\Domain\Event\DomainEvent;

interface DomainEventRepository
{
    public function save(DomainEvent $event): void;
}