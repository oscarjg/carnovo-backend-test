<?php

namespace App\Domain\Cars\ValueObject;

/**
 * Class CarSearchCriteria
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\ValueObject
 */
class FavoriteCarSearchFilter
{
    /**
     * @var array
     */
    private $carsId;

    /**
     * FavoriteCarSearchFilter constructor.
     *
     * @param array $carsId
     */
    public function __construct(array $carsId)
    {
        $this->carsId = $carsId;
    }

    /**
     * @return array
     */
    public function getCarsId(): array
    {
        return $this->carsId;
    }
}
