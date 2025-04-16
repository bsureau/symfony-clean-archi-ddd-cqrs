<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\MarkTaskAsDoneCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class MarkTaskAsDoneController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/todo/{todoId}/task/{taskId}', name: 'mark_task_as_done', methods: ['PUT'])]
    public function __invoke(string $todoId, string $taskId): Response
    {
        $this->commandBus->dispatch(new MarkTaskAsDoneCommand($todoId, $taskId));
        return $this->json(["data" => null], Response::HTTP_OK);
    }
}
