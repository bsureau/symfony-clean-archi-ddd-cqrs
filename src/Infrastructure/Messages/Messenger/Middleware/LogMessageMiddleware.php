<?php

namespace App\Infrastructure\Messages\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class LogMessageMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws \Throwable
     * @throws ExceptionInterface
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $this->logger->info('Starting handler execution', ['message' => $message]);
        try {
            $result = $stack->next()->handle($envelope, $stack);
            $this->logger->info('Handler execution completed', ['message' => $message]);
            return $result;
        } catch (\Throwable $throwable) {
            $this->logger->error('Error processing message', [
                'message' => $message,
                'exception' => $throwable,
            ]);
            throw $throwable;
        }
    }
}