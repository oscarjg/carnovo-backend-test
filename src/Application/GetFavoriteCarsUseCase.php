<?php

namespace App\Application;

use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\Exception\CarSearchCriteriaException;
use App\Domain\Cars\Model\Car;
use App\Domain\Cars\ValueObject\CarSearchCriteria;
use App\Domain\Cars\ValueObject\FavoriteCarSearchFilter;

/**
 * Class GetFavoriteCarsUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application
 */
class GetFavoriteCarsUseCase
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * GetFavoriteCarsUseCase constructor.
     *
     * @param CarRepositoryInterface $repository
     */
    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $cars
     * @param string $orderProperty
     * @param string $orderType
     * @param int $page
     * @param int $limit
     *
     * @return array
     * @throws CarSearchCriteriaException
     */
    public function __invoke(
        array $cars,
        string $orderProperty,
        string $orderType,
        int $page,
        int $limit = CarSearchCriteria::DEFAULT_LIMIT
    ): array {

        $carsId = array_map(function(Car $car) {
            return $car->getId();
        }, $cars);

        return $this
            ->repository
            ->allFavoriteCars(
                new CarSearchCriteria($orderProperty, $orderType, $page, $limit),
                new FavoriteCarSearchFilter($carsId));
    }
}
