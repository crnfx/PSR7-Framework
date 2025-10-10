<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\Response\JsonResponse;

class IndexAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            ["id" => 1, "title" => "First Page"],
            ["id" => 2, "title" => "Second Page"]
        ]);
    }
}