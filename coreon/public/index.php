<?php

declare(strict_types=1);

use Coreon\Core\Application;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$app = (new Application(__DIR__ . '/..'));
$kernel = $app->getHttpKernel();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);