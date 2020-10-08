<?php

namespace App\Domain\Cars\Model;

/**
 * Class Car
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\Model
 */
class Car
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $brand;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var int
     */
    protected $priceEU;

    /**
     * @var int
     */
    protected $priceUS;

    /**
     * Car constructor.
     *
     * @param string $id
     * @param string $brand
     * @param string $model
     * @param int $priceEU
     * @param int $priceUS
     */
    public function __construct(
        string $id,
        string $brand,
        string $model,
        int $priceEU,
        int $priceUS
    ) {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->priceEU = $priceEU;
        $this->priceUS = $priceUS;
    }

    /**
     * @param string $id
     * @param string $brand
     * @param string $model
     * @param int $priceEU
     * @param int $priceUS
     *
     * @return static
     */
    public static function create(
        string $id,
        string $brand,
        string $model,
        int $priceEU,
        int $priceUS
    ): self {
        return new self(
            $id,
            $brand,
            $model,
            $priceEU,
            $priceUS
        );
    }

    /**
     * @param int $euPrice
     * @param int $usPrice
     *
     * @return $this
     */
    public function updatePrices(int $euPrice, int $usPrice): self
    {
        $this->priceEU = $euPrice;
        $this->priceUS = $usPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @return mixed
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return int
     */
    public function getPriceEU(): int
    {
        return $this->priceEU;
    }

    /**
     * @return int
     */
    public function getPriceUS(): int
    {
        return $this->priceUS;
    }
}
