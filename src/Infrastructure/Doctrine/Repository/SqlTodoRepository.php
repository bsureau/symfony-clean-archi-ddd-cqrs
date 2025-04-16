<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Todo;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TodoId;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class SqlTodoRepository implements TodoRepository
{

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    /**
     * @throws Exception
     */
    public function save(Todo $todo): void
    {
        $this->em->persist($todo);
    }

    public function findById(TodoId $id): ?Todo
    {
        return $this->em->getRepository(Todo::class)->findOneBy(['id' => $id]);
    }
}