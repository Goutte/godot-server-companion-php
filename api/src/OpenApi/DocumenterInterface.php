<?php


namespace App\OpenApi;


use ApiPlatform\OpenApi\OpenApi;


/**
 * Make a service implementing this and it'll be used by our ExtendedOpenApiFactory.
 *
 * Interface DocumenterInterface
 * @package App\OpenApi
 */
interface DocumenterInterface
{
    public function document(OpenApi $openApi, array $context) : OpenApi;
}
