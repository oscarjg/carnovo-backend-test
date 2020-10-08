<?php

namespace App\DataFixtures;

use App\Infrastructure\Session\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $uuid = new UuidGenerator();

        $user = (new User(
            $uuid->generateV4(),
            'user-1',
            [
                $this->getReference('car-ford-focus'),
                $this->getReference('car-nissan-juke'),
            ],
            $uuid->generateV4()
        ))->encodePassword(
            $this->passwordEncoder,
            "1234"
        );

        $manager->persist($user);
        $this->setReference('user-1', $user);

        $user = (new User(
            $uuid->generateV4(),
            'user-2',
            [],
            $uuid->generateV4()
        ))->encodePassword(
            $this->passwordEncoder,
            "1234"
        );

        $manager->persist($user);
        $this->setReference('user-2', $user);

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ["users"];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            CarFixtures::class
        ];
    }
}
