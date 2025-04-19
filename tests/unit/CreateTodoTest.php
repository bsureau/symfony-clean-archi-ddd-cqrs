<?php

use App\Application\Command\CreateTodoCommand;
use App\Application\Command\CreateTodoCommandHandler;
use App\Domain\Exception\InvalidTodoNameException;
use App\Infrastructure\Database\InMemory\Repository\InMemoryDomainEventRepository;
use App\Infrastructure\Database\InMemory\Repository\InMemoryTodoRepository;

describe('Create todo', function () {

    it('should add a todo', function () {
        //GIVEN
        $createTodoCommand = new CreateTodoCommand('ma todo');
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $createTodoCommandHandler = new CreateTodoCommandHandler($todoRepository, $domainEventRepository);

        //WHEN
        $createTodoCommandHandler($createTodoCommand);

        //THEN
        expect($todoRepository->todos[0]->getName())->toBe('ma todo');
    });

    it('should save todo created event when a task is added', function () {
        //GIVEN
        $createTodoCommand = new CreateTodoCommand('ma todo');
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $createTodoCommandHandler = new CreateTodoCommandHandler($todoRepository, $domainEventRepository);

        //WHEN
        $createTodoCommandHandler($createTodoCommand);

        //THEN
        expect($domainEventRepository->events[0]->getType())->toBe('todo_created');
    });

    it('throws an exception if name is invalid', function (string $name) {
        //GIVEN
        $createTodoCommand = new CreateTodoCommand($name);
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $createTodoCommandHandler = new CreateTodoCommandHandler($todoRepository, $domainEventRepository);

        //WHEN/GIVEN
        $this->expectException(InvalidTodoNameException::class);
        $this->expectExceptionMessage("Name {$name} must be between 1 and 50 characters long.");
        $createTodoCommandHandler($createTodoCommand);
    })->with([
        [''],
        ['abcdefghijklmnopqrstabcdefghijklmnopqrstabcdefghijklmnopqrstabcdefghijklmnopqrst'],
    ]);
});