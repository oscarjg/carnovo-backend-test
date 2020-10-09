<?php

namespace App\Infrastructure\Exception;

use App\Domain\Cars\Exception\CarSearchCriteriaException;
use App\Domain\Cars\Exception\FavoriteCarNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class HttpBadRequestExceptionFactory
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Exception
 */
class HttpBadRequestExceptionFactory implements HttpExceptionFactoryInterface
{
    /**
     * @param \Throwable $throwable
     *
     * @return HttpExceptionInterface
     */
    public function create(\Throwable $throwable): HttpExceptionInterface
    {
        return new BadRequestHttpException(
            $throwable->getMessage(),
            $throwable
        );
    }

    /**
     * @return string[]
     */
    public function supportedExceptions(): array
    {
        return [
            CarSearchCriteriaException::class,
            FavoriteCarNotFoundException::class
        ];
    }

    /**
     * @param \Throwable $throwable
     *
     * @return bool
     */
    public function isSupported(\Throwable $throwable): bool
    {
        return in_array(get_class($throwable), $this->supportedExceptions());
    }
}
