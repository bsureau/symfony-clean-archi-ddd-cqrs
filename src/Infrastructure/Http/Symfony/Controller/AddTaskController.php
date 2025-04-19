<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Symfony\Controller;

use App\Application\Command\AddTaskCommand;
use App\Infrastructure\Http\Symfony\DTO\AddTaskDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class AddTaskController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/todo/{todoId}/task', name: 'create_task', methods: ['POST'])]
    public function __invoke(string $todoId, #[MapRequestPayload] AddTaskDto $addTaskDto): Response
    {
        $this->commandBus->dispatch(new AddTaskCommand($todoId, $addTaskDto->name));
        return $this->json(["data" => null], Response::HTTP_CREATED);
    }
}
