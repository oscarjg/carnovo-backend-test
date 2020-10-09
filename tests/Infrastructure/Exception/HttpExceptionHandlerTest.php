<?php

namespace Tests\Infrastructure\Exception;

use App\Domain\Cars\Exception\CarSearchCriteriaException;
use App\Domain\Cars\Exception\FavoriteCarNotFoundException;
use App\Infrastructure\Exception\HttpBadRequestExceptionFactory;
use App\Infrastructure\Exception\HttpExceptionHandler;
use Tests\AbstractTestCase;

/**
 * Class HttpExceptionHandlerTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Infrastructure\Exception
 */
class HttpExceptionHandlerTest extends AbstractTestCase
{
    /**
     * @param $exception
     * @param $expectedCode
     * @param $expectedMessage
     *
     * @dataProvider providerData
     */
    public function testHandlerKnownException($exception, $expectedCode, $expectedMessage)
    {
        $handler = new HttpExceptionHandler($this->factories());

        $expectedException = $handler->__invoke($exception);

        $this->assertEquals($expectedCode, $expectedException->getStatusCode());
        $this->assertEquals($expectedMessage, $expectedException->getMessage());
    }

    public function testHandlerUnknownException()
    {
        $handler = new HttpExceptionHandler($this->factories());

        $expectedException = $handler->__invoke(new \Exception());

        $this->assertNull($expectedException);
    }

    public function providerData(): array
    {
        return [
            [new CarSearchCriteriaException("foo error"), 400, "foo error"],
            [new FavoriteCarNotFoundException("bar error"), 400, "bar error"],
        ];
    }

    private function factories(): array
    {
        return [
            new HttpBadRequestExceptionFactory(),
        ];
    }
}
