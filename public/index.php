<?php

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Router;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ?? 'Гость';
    return new HtmlResponse('Hello, ' . $name . '!');
});

$routes->get('about', '/about', function () {
    return new HtmlResponse("amma website");
});

$routes->get('blog', '/blog', function () {
    return new JsonResponse([
        ["id" => 1, "title" => "First Page"],
        ["id" => 2, "title" => "Second Page"]
    ]);
});

$routes->get('blog_show', '/blog/{id}', function (ServerRequestInterface $request) {
    $id = $request->getAttribute('id');
    if ($id > 5) {
        return new JsonResponse(["error" => "Undefined Page"], 404);
    }
    return new JsonResponse(["id" => $id, "title" => "Post #" . $id]);
}, ["id" => "\d+"]);

$router = new Router($routes);

### Running

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    /** @var callable $action **/
    $action = $result->getHandler();
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(["error" => "Undefined Page"], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Roman');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);