<?php

namespace App\Domain\Cars\Contract;

/**
 * Interface CarIdGeneratorInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\Contract
 */
interface CarIdGeneratorInterface
{
    public function generate(): string;
}
