<?php

namespace App\Infrastructure\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Interface HttpExceptionFactoryInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Exception
 */
interface HttpExceptionFactoryInterface
{
    public function create(\Throwable $throwable): HttpExceptionInterface;
    public function supportedExceptions(): array;
    public function isSupported(\Throwable $throwable): bool;
}
