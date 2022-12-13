<?php

namespace App\OpenApi\Documenter;

use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;
use App\OpenApi\DocumenterInterface;

final class MeDocumenter implements DocumenterInterface
{
    public function document(OpenApi $openApi, array $context) : OpenApi
    {

        // Paths, unlike Components, are arrays instead of ArrayObject, hence suffers from COW.
        // Perhaps it's on purpose, but I can't fathom why.
        // For now all the doc is in the Get annot in Player.

        // testing things
//        $openApi->getPaths()->addPath('/meee', $pathItem);
//        $paths = $openApi->getPaths();
//        $p =& $paths->getPaths();
////        dump($p);
//        $me = $paths->getPath('/me')->withSummary("HOHO")->withDescription("ffffff");
//        $p['/me'] = $me;
////        dump($p);
//        $openApi = $openApi->withPaths($paths);
////        $p = $openApi->getPaths()->getPaths();
////        dd($p);
//        dd($openApi->getPaths()->getPaths());

        return $openApi;
    }
}
