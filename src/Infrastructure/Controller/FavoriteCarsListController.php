<?php

namespace App\Infrastructure\Controller;

use App\Application\GetFavoriteCarsUseCase;
use App\Domain\Cars\Exception\CarSearchCriteriaException;
use App\Domain\Cars\ValueObject\CarSearchCriteria;
use App\Domain\Users\Contract\UserFavoriteCarRepositoryInterface;
use App\Domain\Users\Contract\UserStorageInterface;
use App\Infrastructure\Factory\HttpApiResponseFactory;
use App\Infrastructure\Repository\DoctrineCarRepository;
use App\Infrastructure\Serializer\CarSerializer;
use App\Infrastructure\Session\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CarsListController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/api/cars/favorites", methods={"GET"})
 */
class FavoriteCarsListController
{
    /**
     * @var CarSerializer
     */
    private $serializer;

    /**
     * @var UserFavoriteCarRepositoryInterface
     */
    private $repository;

    /**
     * @var UserStorageInterface
     */
    private $userStorage;

    /**
     * FavoriteCarsListController constructor.
     *
     * @param CarSerializer $serializer
     * @param DoctrineCarRepository $repository
     * @param UserStorageInterface $userStorage
     */
    public function __construct(
        CarSerializer $serializer,
        DoctrineCarRepository $repository,
        UserStorageInterface $userStorage
    ) {
        $this->serializer = $serializer;
        $this->repository = $repository;
        $this->userStorage = $userStorage;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws CarSearchCriteriaException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $cars  = (new GetFavoriteCarsUseCase($this->repository))(
            $this->userStorage->fetchUser()->getFavoriteCars(),
            $request->query->get("property", "brand"),
            $request->query->get("order", "asc"),
            $request->query->get("page", 1),
            $request->query->get("limit", CarSearchCriteria::DEFAULT_LIMIT)
        );

        return HttpApiResponseFactory::createFromArray(
            $this->serializer->serialize($cars)
        );
    }
}
