<?php

namespace App\Infrastructure\Helpers;

use App\Domain\Cars\Contract\CarPriceClientInterface;
use App\Domain\Cars\ValueObject\BrandModelCar;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

/**
 * Class PriceUEResolver
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helpers
 */
class CarPriceResolver implements CarPriceClientInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * PriceUEResolver constructor.
     *
     * @param $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param BrandModelCar $brandModelCar
     *
     * @return int
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function fetchPrice(BrandModelCar $brandModelCar): array
    {
        $request = new Request(
            'GET',
            'http://www.randomnumberapi.com/api/v1.0/random?min=15000&max=65000&count=2'
        );

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception(sprintf("Error to fetch price from API. %s", $response->getReasonPhrase()));
        }

        return json_decode($response->getBody(), true);
    }
}
