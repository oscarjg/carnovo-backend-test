<?php

namespace App\Infrastructure\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiExceptionListener
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\EventListener
 */
class ApiExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpException ?
            $exception->getStatusCode() :
            Response::HTTP_INTERNAL_SERVER_ERROR;

        $response = new JsonResponse(
            [
                "detail" => $exception->getMessage(),
                "status" => $statusCode,
                "title"  => "API exception"
            ],
            $statusCode
        );

        $event->setResponse($response);
    }
}
