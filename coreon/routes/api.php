<?php

/** @var Sparkify\Core\Routing\Router $router */

$router->get('/api/health', [\App\Http\Controllers\HealthController::class, 'index'], 'api.health');
$router->get('/api/v1/hello/{name}', [\App\Http\Controllers\HelloController::class, 'greet'], 'api.v1.hello');