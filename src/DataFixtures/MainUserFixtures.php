<?php

namespace App\DataFixtures;

use App\Infrastructure\Session\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\FixtureGroupInterface;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MainUserFixtures
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\DataFixtures
 */
class MainUserFixtures extends Fixture implements FixtureGroupInterface
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
            'user',
            [],
            $uuid->generateV4()
        ))->encodePassword(
            $this->passwordEncoder,
            "1234"
        );

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ["installation"];
    }
}
