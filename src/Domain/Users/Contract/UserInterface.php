<?php

namespace App\Domain\Users\Contract;

use App\Domain\Cars\Model\Car;

/**
 * Class UserInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Users\Contract
 */
interface UserInterface
{
    public function markFavoriteCar(Car $car): self;
    public function unMarkFavoriteCar(Car $car): self;
    public function favoriteCars();
}
