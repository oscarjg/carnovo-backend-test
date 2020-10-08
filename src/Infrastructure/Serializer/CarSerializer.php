<?php

namespace App\Infrastructure\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CarSerializer
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure
 */
class CarSerializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CarSerializer constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $carsOrCars
     *
     * @return mixed
     */
    public function serialize($carsOrCars)
    {
        return json_decode(
            $this->serializer->serialize(
                $carsOrCars,
                "json",
                [
                    AbstractNormalizer::ATTRIBUTES => ['id','brand','model','priceEU', 'priceUS']
                ]
            ),
            true
        );
    }
}
