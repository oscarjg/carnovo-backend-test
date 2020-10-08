<?php

namespace App\Infrastructure\Helpers;

use App\Domain\Cars\ValueObject\BrandModelCar;

/**
 * Class FileReader
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helpers
 */
class FileLineToBrandModelCar
{
    /**
     * @var string
     */
    protected $line;

    /**
     * @var int
     */
    protected $targetColumn;

    /**
     * FileLineToBrandAndModel constructor.
     *
     * @param string $line
     * @param int $targetColumn
     */
    public function __construct(string $line, int $targetColumn)
    {
        $this->line = $line;
        $this->targetColumn = $targetColumn;
    }

    /**
     * @return BrandModelCar
     */
    public function __invoke(): BrandModelCar
    {
        $columns = explode("\t", $this->line);

        if (!isset($columns[$this->targetColumn])) {
            throw new \Exception("Invalid column");
        }

        [$brand, $model] = explode(":", $columns[$this->targetColumn]);

        return new BrandModelCar($brand, $model);
    }
}
