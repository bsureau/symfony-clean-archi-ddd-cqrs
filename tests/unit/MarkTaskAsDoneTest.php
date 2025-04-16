<?php

use App\Application\Command\MarkTaskAsDoneCommand;
use App\Application\Command\MarkTaskAsDoneCommandHandler;
use App\Domain\Entity\Task;
use App\Domain\Entity\Todo;
use App\Domain\Exception\TaskNotFoundException;
use App\Domain\Exception\TodoNotFoundException;
use App\Domain\VO\TaskId;
use App\Domain\VO\TodoId;
use App\Infrastructure\Doctrine\Repository\InMemoryTodoRepository;
use Ramsey\Uuid\Uuid;

describe('Mark task as done', function () {
    it('should mark todo as done', function () {
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $taskId = TaskId::create(Uuid::uuid4());
        $task = Task::create($taskId, 'my task');
        $todo->addTask($task);
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->save($todo);

        $markTaskAsDoneCommand = new MarkTaskAsDoneCommand($todoId, $taskId);
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->todos[] = $todo;
        $markTaskAsDoneCommandHandler = new MarkTaskAsDoneCommandHandler($todoRepository);
        $markTaskAsDoneCommandHandler($markTaskAsDoneCommand);
        expect($todoRepository->todos[0]->getTasks()[0]->getStatus())->toBe(Task::STATUS_DONE);
    });

    it('should throw an exception if todo is not found', function () {
        $todoId = Uuid::uuid4();
        $taskId = Uuid::uuid4();
        $markTaskAsDoneCommand = new MarkTaskAsDoneCommand($todoId, $taskId);
        $todoRepository = new InMemoryTodoRepository();
        $markTaskAsDoneCommandHandler = new MarkTaskAsDoneCommandHandler($todoRepository);
        expect(fn() => $markTaskAsDoneCommandHandler($markTaskAsDoneCommand))->toThrow(new TodoNotFoundException($todoId));
    });

    it('should throw an exception if task is not found', function () {
        $todoId = TodoId::create(Uuid::uuid4());
        $todo = Todo::create($todoId, 'my todo');
        $todoRepository = new InMemoryTodoRepository();
        $todoRepository->save($todo);
        $taskId = Uuid::uuid4();
        $markTaskAsDoneCommand = new MarkTaskAsDoneCommand($todoId, $taskId);
        $markTaskAsDoneCommandHandler = new MarkTaskAsDoneCommandHandler($todoRepository);
        expect(fn() => $markTaskAsDoneCommandHandler($markTaskAsDoneCommand))->toThrow(new TaskNotFoundException($taskId));
    });
});
