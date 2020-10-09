<?php

namespace App\Application;

use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\Exception\FavoriteCarNotFoundException;
use App\Domain\Users\Contract\UserRepositoryInterface;
use App\Domain\Users\Model\User;

/**
 * Class MarkFavoriteCarsUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application
 */
class MarkFavoriteCarsUseCase
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

        $user->markFavoriteCar($car);

        $this
            ->userRepository
            ->updateUser($user);
    }
}
