<?php

namespace App\Infrastructure\Controller;

use App\Application\MarkFavoriteCarsUseCase;
use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Users\Contract\UserRepositoryInterface;
use App\Domain\Users\Contract\UserStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CarsListController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/api/cars/favorites/mark", methods={"PUT"})
 */
class FavoriteCarsMarkController
{
    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserStorageInterface
     */
    private $userStorage;

    /**
     * FavoriteCarsMarkController constructor.
     *
     * @param CarRepositoryInterface $carRepository
     * @param UserRepositoryInterface $userRepository
     * @param UserStorageInterface $userStorage
     */
    public function __construct(
        CarRepositoryInterface $carRepository,
        UserRepositoryInterface $userRepository,
        UserStorageInterface $userStorage
    ) {
        $this->carRepository = $carRepository;
        $this->userRepository = $userRepository;
        $this->userStorage = $userStorage;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $carId = json_decode($request->getContent())->carId;

        (new MarkFavoriteCarsUseCase($this->userRepository, $this->carRepository))(
            $carId,
            $this->userStorage->fetchUser()
        );

        return JsonResponse::create();
    }
}
