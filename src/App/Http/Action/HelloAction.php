<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class HelloAction
{
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $name = $request->getQueryParams()['name'] ?? 'Гость';

        return new HtmlResponse('Hello, ' . $name . '!');
    }
}