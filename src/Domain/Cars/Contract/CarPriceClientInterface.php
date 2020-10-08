<?php

namespace App\Domain\Cars\Contract;

use App\Domain\Cars\ValueObject\BrandModelCar;

/**
 * Interface CarPriceClientInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\Contract
 */
interface CarPriceClientInterface
{
    public function fetchPrice(BrandModelCar $brandModelCar): int;
}
