<?php

namespace App\Infrastructure\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class HttpExceptionHandler
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Exception
 */
class HttpExceptionHandler
{
    /**
     * @var HttpExceptionFactoryInterface[]
     */
    protected $exceptionFactories;

    /**
     * HttpExceptionHandler constructor.
     *
     * @param $exceptionFactories
     */
    public function __construct(iterable $exceptionFactories)
    {
        $this->exceptionFactories = $exceptionFactories;
    }

    /**
     * @param \Throwable $throwable
     *
     * @return HttpExceptionInterface|null
     */
    public function __invoke(\Throwable $throwable): ?HttpExceptionInterface
    {
        $exception = null;

        foreach($this->exceptionFactories as $exceptionFactory) {
            if (!$exceptionFactory->isSupported($throwable)) {
                continue;
            }

            $exception = $exceptionFactory->create($throwable);

            break;
        }

        return $exception;
    }
}
