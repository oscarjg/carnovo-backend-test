<?php

namespace App\Infrastructure\Controller;

use App\Application\GetCarsByCriteriaUseCase;
use App\Domain\Cars\Contract\CarRepositoryInterface;
use App\Domain\Cars\Exception\CarSearchCriteriaException;
use App\Domain\Cars\ValueObject\CarSearchCriteria;
use App\Infrastructure\Factory\HttpApiResponseFactory;
use App\Infrastructure\Serializer\CarSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CarsListController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/api/cars", methods={"GET"})
 */
class CarsListController
{
    /**
     * @var CarSerializer
     */
    private $serializer;

    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * CarsListController constructor.
     *
     * @param CarSerializer $serializer
     * @param CarRepositoryInterface $repository
     */
    public function __construct(
        CarSerializer $serializer,
        CarRepositoryInterface $repository
    ) {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws CarSearchCriteriaException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $cars  = (new GetCarsByCriteriaUseCase($this->repository))(
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
