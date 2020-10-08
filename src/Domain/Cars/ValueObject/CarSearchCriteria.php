<?php

namespace App\Domain\Cars\ValueObject;

use App\Domain\Cars\Exception\CarSearchCriteriaException;

/**
 * Class CarSearchCriteria
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\Cars\ValueObject
 */
class CarSearchCriteria
{
    const DEFAULT_LIMIT = 10;

    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $order;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * CarSearchCriteria constructor.
     *
     * @param string $property
     * @param string $order
     * @param int $page
     * @param int $limit
     *
     * @throws CarSearchCriteriaException
     */
    public function __construct(
        string $property,
        string $order,
        int $page,
        int $limit = self::DEFAULT_LIMIT
    ){
        $this->property = $property;
        $this->order    = $order;
        $this->page     = $page;
        $this->limit    = $limit;

        $this->validate();
    }

    /**
     * @return void
     * @throws CarSearchCriteriaException
     */
    private function validate(): void
    {
        if (!in_array($this->property, ["brand", "model", "priceEU", "priceUS"])) {
            $this->throwException("Invalid property supplied. Only brand, model, priceEU or priceUS are accepted");
        }

        if (!in_array($this->order, ["asc", "desc"])) {
            $this->throwException("Invalid order supplied. Only asc, desc, are accepted");
        }

        if ($this->page < 0) {
            $this->throwException("Invalid page supplied. Page must be a positive value");
        }

        if ($this->limit < 0) {
            $this->throwException("Invalid limit supplied. Limit must be a positive value");
        }
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param string $message
     *
     * @throws CarSearchCriteriaException
     */
    private function throwException(string $message)
    {
        throw new CarSearchCriteriaException($message);
    }
}
