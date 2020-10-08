<?php

namespace App\DataFixtures;

use App\Domain\Cars\Model\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CarFixtures
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\DataFixtures
 */
class CarFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $uuid = new UuidGenerator();

        $car = new Car(
            $uuid->generateV4(),
            "Nissan",
            "Juke",
            21000,
            20000
        );

        $manager->persist($car);
        $this->setReference('car-nissan-juke', $car);

        $car = new Car(
            $uuid->generateV4(),
            "Nissan",
            "Almera",
            31000,
            30000,
            new ArrayCollection([
                //$this->getReference('user-2')
            ])
        );

        $manager->persist($car);
        $this->setReference('car-nissan-almera', $car);

        $car = new Car(
            $uuid->generateV4(),
            "Ford",
            "Focus",
            41000,
            40000,
            new ArrayCollection([])
        );

        $manager->persist($car);
        $this->setReference('car-ford-focus', $car);

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ["cars"];
    }
}
