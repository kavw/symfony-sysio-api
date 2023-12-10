<?php

declare(strict_types=1);

namespace App\Listener;

use App\DTO\Response\Error;
use App\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

readonly class ExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
        private bool $debug,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpException) {
            $event->setResponse(
                new JsonResponse(
                    new Error($exception->getMessage(), null),
                    $exception->getStatusCode(),
                    $exception->getHeaders()
                )
            );
            return;
        }

        if ($exception instanceof ValidationException) {
            $event->setResponse(
                new JsonResponse(
                    new Error($exception->getMessage(), $exception->getMessages()),
                    Response::HTTP_BAD_REQUEST
                )
            );
            return;
        }

        if (
            $exception instanceof \DomainException ||
            $exception instanceof NotEncodableValueException
        ) {
            $event->setResponse(
                new JsonResponse(
                    new Error($exception->getMessage(), null),
                    Response::HTTP_BAD_REQUEST
                )
            );
            return;
        }

        if (!$this->debug) {
            $event->setResponse(
                new JsonResponse(
                    new Error("An error has occurred", null),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                )
            );
        }

        $this->logger->emergency("An exception has been raised", ['exception' => $exception]);
    }
}
