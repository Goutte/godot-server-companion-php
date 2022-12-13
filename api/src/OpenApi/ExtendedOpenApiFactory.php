<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;

/**
 * Helps documenting the OAS from multiple Documenter classes, one for each endpoint or task.
 *
 * Class ExtendedOpenApiFactory
 * @package App\OpenApi
 */
final class ExtendedOpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated,
        private iterable $documenters
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        foreach ($this->documenters as $documenter) {
            /** @var $documenter DocumenterInterface */
            $documenter->document($openApi, $context);
        }

        return $openApi;
    }
}
