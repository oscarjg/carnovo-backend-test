<?php

namespace App\Infrastructure\Helpers;

/**
 * Class FileReader
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helpers
 */
class FileLinesToArray
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * FileReader constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function __invoke(): array
    {
        return explode("\n", file_get_contents($this->filePath));
    }
}
