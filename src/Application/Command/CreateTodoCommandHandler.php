<?php

namespace App\Application\Command;

use App\Domain\Model\Todo;
use App\Domain\Repository\DomainEventRepository;
use App\Domain\Repository\TodoRepository;
use App\Domain\VO\TodoId;
use Ramsey\Uuid\Uuid;

class CreateTodoCommandHandler extends CommandHandler
{
    public function __construct(
        private TodoRepository $todoRepository,
        DomainEventRepository  $domainEventRepository
    )
    {
        parent::__construct($domainEventRepository);
    }

    public function __invoke(CreateTodoCommand $createTodoCommand): void
    {
        $todo = $this->createTodo($createTodoCommand->name);
        $this->todoRepository->add($todo);
        $this->saveDomainEvents($todo->pullDomainEvents());
    }

    public function createTodo(string $name): Todo
    {
        $todoId = TodoId::create(Uuid::uuid4());
        return Todo::create($todoId, $name);
    }
}