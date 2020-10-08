<?php

namespace App\Infrastructure\Session;

use App\Domain\Cars\Model\Car;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Session
 */
class User extends \App\Domain\Users\Model\User implements UserInterface
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    private $salt;

    /**
     * User constructor.
     *
     * @param string $id
     * @param string $username
     * @param array $favoriteCars
     * @param string $salt
     */
    public function __construct(
        string $id,
        string $username,
        array $favoriteCars,
        string $salt
    ) {
        parent::__construct($id, $favoriteCars);

        $this->username = $username;
        $this->salt = $salt;
    }

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param $password
     *
     * @return $this
     */
    public function encodePassword(UserPasswordEncoderInterface $passwordEncoder, $password): self
    {
        $this->password = $passwordEncoder->encodePassword($this, $password);

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        // TODO
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return ["ROLE_USER"];
    }

    /**
     * @return array
     */
    public function getFavoriteCars(): array
    {
        $favoriteCars = parent::getFavoriteCars();

        if ($favoriteCars instanceof Collection) {
            return $favoriteCars->toArray();
        }

        return $favoriteCars;
    }

    /**
     * @param Car $car
     *
     * @return \App\Domain\Users\Model\User
     */
    public function unMarkFavoriteCar(Car $car): parent
    {
        if ( $this->favoriteCars instanceof Collection) {
            $this->favoriteCars = $this->favoriteCars->toArray();
        }

        return parent::unMarkFavoriteCar($car);
    }
}
