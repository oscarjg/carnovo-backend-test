<?php

namespace App\Infrastructure\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class HttpResponseStatusHelper
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helper
 */
class HttpApiResponseFactory
{
    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public static function createFromArray(array $data): JsonResponse
    {
        return JsonResponse::create([
            "data" => $data,
        ], self::resolveStatusCode($data));
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public static function resolveStatusCode(array $data): int
    {
        return count($data) === 0 ?
            JsonResponse::HTTP_NO_CONTENT :
            JsonResponse::HTTP_OK;
    }
}
