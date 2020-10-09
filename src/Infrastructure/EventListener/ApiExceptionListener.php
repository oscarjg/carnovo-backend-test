<?php

namespace App\Infrastructure\EventListener;

use App\Infrastructure\Exception\HttpExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ApiExceptionListener
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\EventListener
 */
class ApiExceptionListener
{
    /**
     * @var HttpExceptionHandler
     */
    protected $httpHandler;

    /**
     * ApiExceptionListener constructor.
     *
     * @param HttpExceptionHandler $httpHandler
     */
    public function __construct(HttpExceptionHandler $httpHandler)
    {
        $this->httpHandler = $httpHandler;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $throwException = $event->getThrowable();

        $exception = $this->httpHandler->__invoke($throwException);

        $statusCode = $exception instanceof HttpExceptionInterface ?
            $exception->getStatusCode() :
            Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = $exception instanceof HttpExceptionInterface ?
            $exception->getMessage() :
            $throwException->getMessage();

        $response = new JsonResponse(
            [
                "detail" => $message,
                "status" => $statusCode,
                "title"  => "API exception"
            ],
            $statusCode
        );

        $event->setResponse($response);
    }
}
