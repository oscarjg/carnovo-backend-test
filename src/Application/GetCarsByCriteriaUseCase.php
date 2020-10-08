<?php

namespace App\Application;

use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\Exception\CarSearchCriteriaException;
use App\Domain\Cars\ValueObject\CarSearchCriteria;

class GetCarsByCriteriaUseCase
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * GetCarsByCriteriaUseCase constructor.
     *
     * @param CarRepositoryInterface $repository
     */
    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $orderProperty
     * @param string $orderType
     * @param int $page
     * @param int $limit
     *
     * @return array
     * @throws CarSearchCriteriaException
     */
    public function __invoke(
        string $orderProperty,
        string $orderType,
        int $page,
        int $limit = CarSearchCriteria::DEFAULT_LIMIT
    ): array {
        $criteria  = new CarSearchCriteria($orderProperty, $orderType, $page, $limit);

        return $this
            ->repository
            ->allCars($criteria);
    }
}
