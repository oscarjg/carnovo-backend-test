<?php

namespace App\Domain\Users\Contract;

use App\Domain\Users\Model\User;

/**
 * Interface UserRepositoryInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\Contract
 */
interface UserRepositoryInterface
{
    public function updateUser(User $user): User;
}
