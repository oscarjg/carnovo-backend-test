<?php

namespace App\Application;

use App\Domain\Cars\Contract\CarIdGeneratorInterface;
use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\Model\Car;
use App\Domain\Cars\ValueObject\BrandModelCar;

/**
 * Class CarInventoryUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application
 */
class CarInventoryUseCase
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * @var CarIdGeneratorInterface
     */
    private $carIdGenerator;

    /**
     * CarInventoryUseCase constructor.
     *
     * @param CarRepositoryInterface $repository
     * @param CarIdGeneratorInterface $carIdGenerator
     */
    public function __construct(
        CarRepositoryInterface $repository,
        CarIdGeneratorInterface  $carIdGenerator
    ) {
        $this->repository = $repository;
        $this->carIdGenerator = $carIdGenerator;
    }

    /**
     * @param BrandModelCar $brandModelCar
     * @param int $euPrice
     * @param int $usPrice
     *
     * @return Car
     */
    public function __invoke(
        BrandModelCar $brandModelCar,
        int $euPrice,
        int $usPrice
    ): Car {
        $car = $this->handleCar($brandModelCar, $euPrice, $usPrice);

        return $this
            ->repository
            ->saveCar($car);
    }

    /**
     * @param BrandModelCar $brandModelCar
     * @param $euPrice
     * @param $usPrice
     *
     * @return Car
     */
    private function handleCar(BrandModelCar $brandModelCar, $euPrice, $usPrice): Car
    {
        $car = $this
            ->repository
            ->findCarFromCarModel($brandModelCar);

        if ($car === null) {
            return Car::create(
                $this->carIdGenerator->generate(),
                $brandModelCar->getBrand(),
                $brandModelCar->getModel(),
                $euPrice,
                $usPrice
            );
        }

        $car->updatePrices($euPrice, $usPrice);

        return $car;
    }
}
