<?php

namespace App\Application\Command;

use App\Domain\Repository\DomainEventRepository;

class CommandHandler
{

    public function __construct(
        private readonly DomainEventRepository $domainEventRepository,
    )
    {
    }

    protected function saveDomainEvents(array $domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->domainEventRepository->save($domainEvent);
        }
    }
}