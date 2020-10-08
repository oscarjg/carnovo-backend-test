<?php

namespace App\Domain\Cars\Contract;

use App\Domain\Cars\Model\Car;
use App\Domain\Cars\ValueObject\BrandModelCar;
use App\Domain\Cars\ValueObject\CarSearchCriteria;
use App\Domain\Cars\ValueObject\FavoriteCarSearchFilter;

/**
 * Class CarRepositoryInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\Contract
 */
interface CarRepositoryInterface
{
    public function findCar(string $id): ?Car;
    public function findCarFromCarModel(BrandModelCar $brandModelCar): ?Car;
    public function saveCar(Car $car): Car;
    public function allCars(CarSearchCriteria $carSearchCriteria): array;
    public function allFavoriteCars(
        CarSearchCriteria $carSearchCriteria,
        FavoriteCarSearchFilter $favoriteCarSearchFilter
    ): array;
}
