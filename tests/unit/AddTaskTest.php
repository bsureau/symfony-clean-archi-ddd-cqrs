<?php

use App\Application\Command\AddTaskCommand;
use App\Application\Command\AddTaskCommandHandler;
use App\Domain\Entity\Task;
use App\Domain\Entity\Todo;
use App\Domain\Exception\TodoNotFoundException;
use App\Domain\Exception\TooManyTodoTasksException;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use App\Infrastructure\Doctrine\Repository\InMemoryDomainEventRepository;
use App\Infrastructure\Doctrine\Repository\InMemoryTodoRepository;
use Ramsey\Uuid\Uuid;

describe('Add task', function () {

    it('should add a task', function () {
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->save($todo);
        $domainEventRepository = new InMemoryDomainEventRepository();
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);
        $addTaskCommandHandler($addTaskCommand);
        expect($todoRepository->todos[0]->getTasks()[0]->getName())->toBe('buy some eggs')
            ->and($todoRepository->todos[0]->getTasks()[0]->getStatus())->toBe(Task::STATUS_TODO);
    });


    it('should save task added event when a task is added', function () {
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->save($todo);
        $domainEventRepository = new InMemoryDomainEventRepository();
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);
        $addTaskCommandHandler($addTaskCommand);
        expect($domainEventRepository->events[0]->getType())->toBe('task_added');
    });

    it('should throw an exception if todo not found', function () {
        $todoId = TodoId::create(Uuid::uuid4());
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $todoRepository = new InMemoryTodoRepository();
        $domainEventRepository = new InMemoryDomainEventRepository();
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);
        expect(fn() => $addTaskCommandHandler($addTaskCommand))->toThrow(new TodoNotFoundException($todoId));
    });

    it('should throw an exception if there is too many todo tasks', function () {
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        foreach (range(1, 5) as $i) {
            $taskId = TaskId::create(Uuid::uuid4());
            $task = Task::create($taskId, 'my task');
            $todo->addTask($task);
        }
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->save($todo);
        $addTaskCommand = new AddTaskCommand($todoId, 'buy some eggs');
        $domainEventRepository = new InMemoryDomainEventRepository();
        $addTaskCommandHandler = new AddTaskCommandHandler($todoRepository, $domainEventRepository);
        expect(fn() => $addTaskCommandHandler($addTaskCommand))->toThrow(new TooManyTodoTasksException());
    });
});