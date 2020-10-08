<?php

namespace App\Domain\Users\Contract;

use App\Domain\Users\Model\User;

/**
 * Interface UserStorageInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Users\Contract
 */
interface UserStorageInterface
{
    public function fetchUser(): User;
}
