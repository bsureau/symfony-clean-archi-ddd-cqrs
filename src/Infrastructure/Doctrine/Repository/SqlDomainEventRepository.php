<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Event\DomainEvent;
use App\Domain\Repository\DomainEventRepository;
use Doctrine\ORM\EntityManagerInterface;

class SqlDomainEventRepository implements DomainEventRepository
{

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function save(DomainEvent $event): void
    {
        $this->em->persist($event);
    }
}