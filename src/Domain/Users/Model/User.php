<?php

namespace App\Domain\Users\Model;

use App\Domain\Cars\Model\Car;

/**
 * Class User
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Users\Model
 */
class User
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $favoriteCars;

    /**
     * User constructor.
     *
     * @param string $id
     * @param array $favoriteCars
     */
    public function __construct(
        string $id,
        array $favoriteCars
    ) {
        $this->id = $id;
        $this->favoriteCars = $favoriteCars;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFavoriteCars()
    {
        return $this->favoriteCars;
    }

    /**
     * @param Car $car
     *
     * @return $this
     */
    public function markFavoriteCar(Car $car): self
    {
        $this->favoriteCars[] = $car;

        return $this;
    }

    /**
     * @param Car $car
     *
     * @return $this
     */
    public function unMarkFavoriteCar(Car $car): self
    {
        $key = array_search($car, $this->favoriteCars);

        if ($key !== false) {
            unset($this->favoriteCars[$key]);
        }

        return $this;
    }
}
