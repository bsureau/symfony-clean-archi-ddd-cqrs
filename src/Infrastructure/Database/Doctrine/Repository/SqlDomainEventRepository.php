<?php

namespace App\Infrastructure\Database\Doctrine\Repository;

use App\Domain\Event\DomainEvent;
use App\Domain\Repository\DomainEventRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class SqlDomainEventRepository implements DomainEventRepository
{

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    /**
     * @throws Exception
     */
    public function save(DomainEvent $event): void
    {
        $connection = $this->em->getConnection();
        $connection->executeStatement(
            'INSERT INTO outbox (id, event_type, payload) VALUES (:id, :eventType, :payload)',
            [
                'id' => $event->getId(),
                'eventType' => $event->getType(),
                'payload' => $event->getPayload()
            ]
        );
    }
}