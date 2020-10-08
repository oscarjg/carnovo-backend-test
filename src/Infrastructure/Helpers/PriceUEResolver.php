<?php

namespace App\Infrastructure\Helpers;

use App\Domain\Cars\Contract\CarPriceClientInterface;
use App\Domain\Cars\ValueObject\BrandModelCar;

/**
 * Class PriceUEResolver
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helpers
 */
class PriceUEResolver implements CarPriceClientInterface
{
    /**
     * @param BrandModelCar $brandModelCar
     *
     * @return int
     */
    public function fetchPrice(BrandModelCar $brandModelCar): int
    {
        return rand(20000, 40000);
    }
}
