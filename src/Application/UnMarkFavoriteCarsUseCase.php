<?php

namespace App\Application;

use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Users\Contract\UserRepositoryInterface;
use App\Domain\Users\Model\User;
use App\Domain\Cars\Exception\FavoriteCarNotFoundException;

/**
 * Class UnMarkFavoriteCarsUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application
 */
class UnMarkFavoriteCarsUseCase
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;

    /**
     * UnMarkFavoriteCarsUseCase constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        CarRepositoryInterface $carRepository
    ) {
        $this->userRepository = $userRepository;
        $this->carRepository = $carRepository;
    }

    /**
     * @param string $carId
     * @param User $user
     *
     * @throws FavoriteCarNotFoundException
     */
    public function __invoke(
        string $carId,
        User $user
    ): void {

        $car = $this
            ->carRepository
            ->findCar($carId);

        if ($car === null) {
            throw new FavoriteCarNotFoundException(sprintf("Car %s not found", $carId));
        }

        $user->unMarkFavoriteCar($car);

        $this
            ->userRepository
            ->updateUser($user);
    }
}
