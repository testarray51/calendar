<?php

namespace PublicBundle\Listener;

use PublicBundle\Exceptions\ErrorCodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class MessageExceptionListener
{
    /**
     * handle error for a route
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if(method_exists($exception, 'getStatusCode')){
            $statusCode = $exception->getStatusCode();
        }
        if ($exception instanceof HttpExceptionInterface) {
            $previousException = $exception->getPrevious();
            if ($previousException instanceof ErrorCodeInterface) {
                $message = $previousException->getMessage();
            } else {
                $message = $exception->getMessage();
            }
        } else {
            $message = $exception->getMessage();
        }

        $event->setResponse(new JsonResponse(array("message" => $message), $statusCode));
    }
}