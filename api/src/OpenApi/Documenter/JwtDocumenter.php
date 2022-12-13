<?php

namespace App\OpenApi\Documenter;

use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;
use App\OpenApi\DocumenterInterface;

final class JwtDocumenter implements DocumenterInterface
{
    public function document(OpenApi $openApi, array $context): OpenApi
    {
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'toto97',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'apassword',
                ],
            ],
        ]);

        $schemas = $openApi->getComponents()->getSecuritySchemes() ?? [];
        $schemas['JWT'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);

        $pathItem = new Model\PathItem(
//            ref: 'JWT Token', // unsupported by openapi generator (for now)
            post: new Model\Operation(
            operationId: 'postCredentialsItem',
            tags: ['Token'],
            responses: [
            '200' => [
                'description' => 'Get a JWT (authentication token)',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/Token',
                        ],
                    ],
                ],
            ],
        ],
            summary: 'Retrieves a token (JWT) to login.',
            requestBody: new Model\RequestBody(
            description: 'Generates and returns a new Json Web Token (JWT) from provided credentials.',
            content: new \ArrayObject([
            'application/json' => [
                'schema' => [
                    '$ref' => '#/components/schemas/Credentials',
                ],
            ],
        ]),
        ),
            security: [],
        ),
        );
        $openApi->getPaths()->addPath('/authentication_token', $pathItem);

        return $openApi;
    }
}
