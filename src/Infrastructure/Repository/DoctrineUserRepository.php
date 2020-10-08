<?php

namespace App\Infrastructure\Repository;

use App\Domain\Users\Contract\UserRepositoryInterface;
use App\Domain\Users\Model\User;
use App\Infrastructure\Session\User as InfrastructureUser;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\MongoDBException;

/**
 * Class DoctrineUserRepository
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Repository
 */
class DoctrineUserRepository extends ServiceDocumentRepository implements UserRepositoryInterface
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, InfrastructureUser::class);
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws MongoDBException
     */
    public function updateUser(User $user): User
    {
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        return $user;
    }
}
