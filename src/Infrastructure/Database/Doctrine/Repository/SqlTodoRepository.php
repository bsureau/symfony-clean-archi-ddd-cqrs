<?php

namespace App\Infrastructure\Database\Doctrine\Repository;

use App\Domain\Model\Todo;
use App\Domain\Repository\TodoRepository;
use App\Domain\Snapshot\TaskSnapshot;
use App\Domain\Snapshot\TodoSnapshot;
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
    public function add(Todo $todo): void
    {
        $todoSnapshot = $todo->toSnapshot();
        $connection = $this->em->getConnection();
        $connection->executeStatement('INSERT INTO todo (id, name) VALUES (:todoId, :todoName)', [
            'todoId' => $todoSnapshot->getId(),
            'todoName' => $todoSnapshot->getName()
        ]);
    }


    /**
     * @throws Exception
     */
    public function update(Todo $todo): void
    {
        $todoSnapshot = $todo->toSnapshot();
        $connection = $this->em->getConnection();
        $connection->executeStatement('DELETE FROM task WHERE todo_id = :todoId', [
            'todoId' => $todoSnapshot->getId()
        ]);
        $connection->executeStatement('DELETE FROM todo WHERE id = :id', [
            'id' => $todoSnapshot->getId()
        ]);
        $connection->executeStatement('INSERT INTO todo (id, name) VALUES (:todoId, :todoName)', [
            'todoId' => $todoSnapshot->getId(),
            'todoName' => $todoSnapshot->getName()
        ]);
        foreach ($todoSnapshot->getTasks() as $task) {
            $connection->executeStatement('INSERT INTO task (id, name, status, todo_id) VALUES (:taskId, :taskName, :taskStatus, :todoId)', [
                'taskId' => $task->getId(),
                'taskName' => $task->getName(),
                'taskStatus' => $task->getStatus(),
                'todoId' => $todoSnapshot->getId()
            ]);
        }
    }

    /**
     * @throws Exception
     */
    public function findById(TodoId $id): ?Todo
    {
        $connection = $this->em->getConnection();
        $todo = $connection->executeQuery('SELECT * FROM todo WHERE id = :todoId', [
            'todoId' => $id->__toString()
        ])->fetchAssociative();

        $tasks = $connection->executeQuery('SELECT * FROM task WHERE todo_id = :todoId', [
            'todoId' => $id->__toString()
        ])->fetchAllAssociative();

        $tasksSnapshot = [];
        foreach ($tasks as $task) {
            $tasksSnapshot[] = new TaskSnapshot(
                $task['id'],
                $task['name'],
                $task['status'],
            );
        }
        $todoSnapshot = new TodoSnapshot(
            $todo['id'],
            $todo['name'],
            $tasksSnapshot
        );

        return Todo::fromSnapshot($todoSnapshot);
    }
}