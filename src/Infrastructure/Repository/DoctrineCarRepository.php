<?php

namespace App\Infrastructure\Repository;

use App\Domain\Cars\Model\Car;
use App\Domain\Cars\ValueObject\BrandModelCar;
use App\Domain\Cars\ValueObject\CarSearchCriteria;
use App\Domain\Cars\ValueObject\FavoriteCarSearchFilter;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Query\Builder;
use App\Domain\Cars\Contract\CarRepositoryInterface;

/**
 * Class CarRepositoryInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Doctrine
 */
class DoctrineCarRepository extends ServiceDocumentRepository implements CarRepositoryInterface
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Car::class);
    }

    /**
     * @param string $id
     *
     * @return Car|null
     * @throws LockException
     * @throws MappingException
     */
    public function findCar(string $id): ?Car
    {
        return $this->find($id);
    }

    /**
     * @param BrandModelCar $brandModelCar
     *
     * @return Car|null
     */
    public function findCarFromCarModel(BrandModelCar $brandModelCar): ?Car
    {
        return $this
            ->findOneBy([
                "brand" => $brandModelCar->getBrand(),
                "model" => $brandModelCar->getModel()
            ]);
    }

    /**
     * @param Car $car
     *
     * @return Car
     * @throws MongoDBException
     */
    public function saveCar(Car $car): Car
    {
        $this->getDocumentManager()->persist($car);
        $this->getDocumentManager()->flush();

        return $car;
    }

    /**
     * @param CarSearchCriteria $carSearchCriteria
     *
     * @return array
     * @throws MongoDBException
     */
    public function allCars(CarSearchCriteria $carSearchCriteria): array
    {
        return $this
            ->criteriaBuilder($carSearchCriteria)
            ->getQuery()
            ->execute()
            ->toArray()
            ;
    }

    /**
     * @param CarSearchCriteria $carSearchCriteria
     * @param FavoriteCarSearchFilter $carSearchFilter
     *
     * @return array
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function allFavoriteCars(
        CarSearchCriteria $carSearchCriteria,
        FavoriteCarSearchFilter $carSearchFilter
    ): array {
        return $this
            ->criteriaBuilder($carSearchCriteria)
            ->field('id')
            ->in($carSearchFilter->getCarsId())
            ->getQuery()
            ->execute()
            ->toArray()
            ;
    }

    /**
     * @param CarSearchCriteria $carSearchCriteria
     *
     * @return Builder
     */
    private function criteriaBuilder(CarSearchCriteria $carSearchCriteria): Builder
    {
        $page  = $carSearchCriteria->getPage();
        $limit = $carSearchCriteria->getLimit();
        $skip  = $page > 0 ? ($page-1) * $limit : 0;

        return $this
            ->createQueryBuilder()
            ->sort($carSearchCriteria->getProperty(), $carSearchCriteria->getOrder())
            ->limit($limit)
            ->skip($skip)
            ;
    }
}
