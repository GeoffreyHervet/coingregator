<?php

namespace App\EventListener;

use App\Exception\ApiViolationException;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof ApiViolationException) {
            return $event->setResponse(new JsonResponse($exception->toArray(), 400));
        }

        if ($exception instanceof NotFoundException) {
            return $event->setResponse(new JsonResponse(sprintf('%s not found.', $exception->getMessage()), 404));
        }
    }
}
