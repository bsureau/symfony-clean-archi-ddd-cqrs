<?php

namespace App\Infrastructure\Http\Symfony\Listener;

use App\Domain\Exception\TodoNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $responseData = [];
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HandlerFailedException) {
            $originException = $exception->getPrevious();
            $responseData['error'] = $originException->getMessage();
            $statusCode = match (true) {
                $originException instanceof TodoNotFoundException => Response::HTTP_NOT_FOUND,
                default => Response::HTTP_BAD_REQUEST,
            };
        } else {
            $responseData['error'] = 'Something went wrong, please try again later or contact administrators.';
        }

        $response = new JsonResponse($responseData, $statusCode);
        $event->setResponse($response);
    }
}
