<?php

use App\Application\Command\CreateTodoCommand;
use App\Application\Command\CreateTodoCommandHandler;
use App\Domain\Exception\InvalidTodoNameException;
use App\Infrastructure\Doctrine\Repository\InMemoryDomainEventRepository;
use App\Infrastructure\Doctrine\Repository\InMemoryTodoRepository;

describe('Create todo', function () {
    it('should add a todo', function () {
        $createTodoCommand = new CreateTodoCommand('ma todo');
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $createTodoCommandHandler = new CreateTodoCommandHandler($todoRepository, $domainEventRepository);
        $createTodoCommandHandler($createTodoCommand);
        expect($todoRepository->todos[0]->getName())->toBe('ma todo');
    });

    it('should save todo created event when a task is added', function () {
        $createTodoCommand = new CreateTodoCommand('ma todo');
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $createTodoCommandHandler = new CreateTodoCommandHandler($todoRepository, $domainEventRepository);
        $createTodoCommandHandler($createTodoCommand);
        expect($domainEventRepository->events[0]->getType())->toBe('todo_created');
    });

    it('throws an exception if name is invalid', function (string $name) {
        $createTodoCommand = new CreateTodoCommand($name);
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $createTodoCommandHandler = new CreateTodoCommandHandler($todoRepository, $domainEventRepository);
        $this->expectException(InvalidTodoNameException::class);
        $this->expectExceptionMessage("Name {$name} must be between 1 and 50 characters long.");
        $createTodoCommandHandler($createTodoCommand);
    })->with([
        [''],
        ['abcdefghijklmnopqrstabcdefghijklmnopqrstabcdefghijklmnopqrstabcdefghijklmnopqrst'],
    ]);
});