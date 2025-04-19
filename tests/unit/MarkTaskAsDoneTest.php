<?php

use App\Application\Command\MarkTaskAsDoneCommand;
use App\Application\Command\MarkTaskAsDoneCommandHandler;
use App\Domain\Exception\TaskNotFoundException;
use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Model\Task;
use App\Domain\Model\Todo;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use App\Infrastructure\Database\InMemory\Repository\InMemoryDomainEventRepository;
use App\Infrastructure\Database\InMemory\Repository\InMemoryTodoRepository;
use Ramsey\Uuid\Uuid;

describe('Mark task as done', function () {
    it('should mark task as done', function () {
        // GIVEN
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $taskId = TaskId::create(Uuid::uuid4());
        $task = Task::create($taskId, 'my task');
        $todo->addTask($task);
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->add($todo);
        $markTaskAsDoneCommand = new MarkTaskAsDoneCommand($todoId, $taskId);
        $domainEventRepository = new InMemoryDomainEventRepository();
        $markTaskAsDoneCommandHandler = new MarkTaskAsDoneCommandHandler($todoRepository, $domainEventRepository);

        // WHEN
        $markTaskAsDoneCommandHandler($markTaskAsDoneCommand);

        // THEN
        expect($todoRepository->todos[0]->getTasks()[0]->getStatus())->toBe(Task::STATUS_DONE);
    });

    it('should throw an exception if todo is not found', function () {
        // GIVEN
        $todoId = Uuid::uuid4();
        $taskId = Uuid::uuid4();
        $markTaskAsDoneCommand = new MarkTaskAsDoneCommand($todoId, $taskId);
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();

        // WHEN
        $markTaskAsDoneCommandHandler = new MarkTaskAsDoneCommandHandler($todoRepository, $domainEventRepository);

        // THEN
        expect(fn() => $markTaskAsDoneCommandHandler($markTaskAsDoneCommand))->toThrow(new TodoNotFoundException($todoId));
    });

    it('should throw an exception if task is not found', function () {
        // GIVEN
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->add($todo);
        $taskId = Uuid::uuid4();
        $markTaskAsDoneCommand = new MarkTaskAsDoneCommand($todoId, $taskId);
        $domainEventRepository = new InMemoryDomainEventRepository();

        // WHEN
        $markTaskAsDoneCommandHandler = new MarkTaskAsDoneCommandHandler($todoRepository, $domainEventRepository);

        // THEN
        expect(fn() => $markTaskAsDoneCommandHandler($markTaskAsDoneCommand))->toThrow(new TaskNotFoundException($taskId));
    });
});
