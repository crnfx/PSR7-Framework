<?php

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$name = !empty($request->getQueryParams()['name']) ? $request->getQueryParams()['name'] : 'Гость';
$response = (new HtmlResponse("Hello,  $name!"))->withHeader('X-Developer', 'Roman');

$emitter = new SapiEmitter();
$emitter->emit($response);