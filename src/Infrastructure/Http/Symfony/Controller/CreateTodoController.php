<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Symfony\Controller;

use App\Application\Command\CreateTodoCommand;
use App\Infrastructure\Http\Symfony\DTO\CreateTodoDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class CreateTodoController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/todo', name: 'create_todo', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateTodoDto $createTodoDto): Response
    {
        $this->commandBus->dispatch(new CreateTodoCommand($createTodoDto->name));
        return $this->json(["data" => null], Response::HTTP_CREATED);
    }
}
