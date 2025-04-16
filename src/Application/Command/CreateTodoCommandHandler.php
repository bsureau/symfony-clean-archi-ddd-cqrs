<?php

namespace App\Application\Command;

use App\Domain\Entity\Todo;
use App\Domain\Repository\DomainEventRepository;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TodoId;
use Ramsey\Uuid\Uuid;

readonly class CreateTodoCommandHandler
{
    public function __construct(
        private TodoRepository        $todoRepository,
        private DomainEventRepository $domainEventRepository
    )
    {
    }

    public function __invoke(CreateTodoCommand $createTodoCommand): void
    {
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, $createTodoCommand->name);
        $this->todoRepository->save($todo);
        $this->saveDomainEvents($todo->pullDomainEvents());
    }

    private function saveDomainEvents(array $pullDomainEvents)
    {
        foreach ($pullDomainEvents as $domainEvent) {
            $this->domainEventRepository->save($domainEvent);
        }
    }
}