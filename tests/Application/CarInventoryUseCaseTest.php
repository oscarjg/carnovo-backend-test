<?php

namespace Tests\Application;

use App\Application\CarInventoryUseCase;
use App\Domain\Cars\Contract\CarIdGeneratorInterface;
use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\Model\Car;
use App\Domain\Cars\ValueObject\BrandModelCar;
use Tests\AbstractTestCase;

/**
 * Class CarInventoryUseCaseTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application
 */
class CarInventoryUseCaseTest extends AbstractTestCase
{
    public function testCarInventoryUseCaseCreate()
    {
        $useCase = new CarInventoryUseCase(
            $this->mockCarRepositoryNewUseCase(),
            $this->mockCarIdGenerator()
        );

        $car = $useCase->__invoke(
            new BrandModelCar("foo", "bar"),
            10000,
            20000
        );

        $this->assertEquals( "foo-uuid", $car->getId());
    }

    public function testCarInventoryUseCaseUpdate()
    {
        $brandModel = new BrandModelCar("foo", "bar");
        $carToUpdate = Car::create(
            "bar-uuid",
            $brandModel->getBrand(),
            $brandModel->getModel(),
            20000,
            21000
        );

        $useCase = new CarInventoryUseCase(
            $this->mockCarRepositoryUpdateUseCase($carToUpdate),
            $this->mockCarIdGenerator()
        );

        $car = $useCase->__invoke(
            new BrandModelCar("foo", "bar"),
            40000,
            50000
        );

        $this->assertEquals( "bar-uuid", $car->getId());
        $this->assertEquals( "bar", $car->getModel());
        $this->assertEquals( "foo", $car->getBrand());
        $this->assertEquals( 40000, $car->getPriceEU());
        $this->assertEquals( 50000, $car->getPriceUS());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCarRepositoryNewUseCase()
    {
        $mock = $this->createMock(CarRepositoryInterface::class);

        $mock
            ->method('findCarFromCarModel')
            ->willReturn(null);

        $mock
            ->method('saveCar')
            ->willReturnCallback(function(Car $car){
                return $car;
            });

        return $mock;
    }

    /**
     * @param Car $carFromRepo
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCarRepositoryUpdateUseCase(Car $carFromRepo)
    {
        $mock = $this->createMock(CarRepositoryInterface::class);

        $mock
            ->method('findCarFromCarModel')
            ->willReturnCallback(function(BrandModelCar $car) use ($carFromRepo){
                return $carFromRepo;
            });

        $mock
            ->method('saveCar')
            ->willReturnCallback(function(Car $car){
                return $car;
            });

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCarIdGenerator()
    {
        $mock = $this->createMock(CarIdGeneratorInterface::class);

        $mock
            ->method('generate')
            ->willReturn("foo-uuid");

        return $mock;
    }
}
