<?php

namespace App\Infrastructure\Session;

use App\Domain\Users\Contract\UserStorageInterface;
use App\Domain\Users\Model\User;
use App\Infrastructure\Exception\InvalidUserSessionException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserStorage
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Session
 */
class UserStorage implements UserStorageInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * UserStorage constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User
     * @throws InvalidUserSessionException
     */
    public function fetchUser(): User
    {
        $user = $this
            ->tokenStorage
            ->getToken()
            ->getUser();

        if (!$user instanceof User) {
            throw new InvalidUserSessionException("Invalid user storage in session");
        }

        return $user;
    }
}
