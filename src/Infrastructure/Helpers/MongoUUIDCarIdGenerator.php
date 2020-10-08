<?php

namespace App\Infrastructure\Helpers;

use App\Domain\Cars\Contract\CarIdGeneratorInterface;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;

/**
 * Class MongoUUIDCarIdGenerator
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helpers
 */
class MongoUUIDCarIdGenerator implements CarIdGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        $uuid = new UuidGenerator();

        return $uuid->generateV4();
    }
}
