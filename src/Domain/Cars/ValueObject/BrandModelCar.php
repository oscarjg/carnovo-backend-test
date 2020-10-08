<?php

namespace App\Domain\Cars\ValueObject;

/**
 * Class BrandModelCar
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\ValueObject
 */
class BrandModelCar
{
    /**
     * @var string
     */
    protected $brand;

    /**
     * @var string
     */
    protected $model;

    /**
     * BrandModelCar constructor.
     *
     * @param string $brand
     * @param string $model
     */
    public function __construct(string $brand, string $model)
    {
        $this->brand = $brand;
        $this->model = $model;

        $this->validate();
    }

    /**
     * @throws \Exception
     */
    private function validate(): void
    {
        if (empty($this->brand)) {
            throw new \Exception("Empty brand is not allowed");
        }

        if (empty($this->model)) {
            throw new \Exception("Empty model is not allowed");
        }
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
