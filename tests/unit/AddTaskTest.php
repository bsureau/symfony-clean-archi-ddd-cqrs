<?php

use App\Application\Command\AddTaskCommand;
use App\Application\Command\AddTaskCommandHandler;
use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Exception\TooManyTodoTasksException;
use App\Domain\Model\Task;
use App\Domain\Model\Todo;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use App\Infrastructure\Database\InMemory\Repository\InMemoryDomainEventRepository;
use App\Infrastructure\Database\InMemory\Repository\InMemoryTodoRepository;
use Ramsey\Uuid\Uuid;

describe('Add task', function () {

    it('should add a task', function () {
        // GIVEN
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->add($todo);
        $domainEventRepository = new InMemoryDomainEventRepository();
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);

        // WHEN
        $addTaskCommandHandler($addTaskCommand);

        // THEN
        expect($todoRepository->todos[0]->getTasks()[0]->getName())->toBe('buy some eggs')
            ->and($todoRepository->todos[0]->getTasks()[0]->getStatus())->toBe(Task::STATUS_TODO);
    });

    it('should save task added event when a task is added', function () {
        // GIVEN
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->add($todo);
        $domainEventRepository = new InMemoryDomainEventRepository();
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);

        // WHEN
        $addTaskCommandHandler($addTaskCommand);

        // THEN
        expect($domainEventRepository->events[0]->getType())->toBe('task_added');
    });

    it('should throw an exception if todo not found', function () {
        // GIVEN
        $todoId = TodoId::create(Uuid::uuid4());
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();

        // WHEN
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);

        // THEN
        expect(fn() => $addTaskCommandHandler($addTaskCommand))->toThrow(new TodoNotFoundException($todoId));
    });

    it('should throw an exception if there is too many todo tasks', function () {
        // GIVEN
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        foreach (range(1, 5) as $i) {
            $taskId = TaskId::create(Uuid::uuid4());
            $task = Task::create($taskId, 'my task');
            $todo->addTask($task);
        }
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->add($todo);
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $domainEventRepository = new InMemoryDomainEventRepository();

        // WHEN
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);

        // THEN
        expect(fn() => $addTaskCommandHandler($addTaskCommand))->toThrow(new TooManyTodoTasksException());
    });
});