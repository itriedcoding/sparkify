<?php

/** @var Sparkify\Core\Routing\Router $router */

$router->get('/api/health', [\App\Http\Controllers\HealthController::class, 'index'], 'api.health');
$router->get('/api/metrics', [\App\Http\Controllers\HealthController::class, 'metrics'], 'api.metrics');
$router->get('/api/v1/hello/{name}', [\App\Http\Controllers\HelloController::class, 'greet'], 'api.v1.hello');

// Example: Protect a route via JWT middleware (wire in kernel or dedicated group handling)
// $router->get('/api/v1/secure', [\App\Http\Controllers\SecureController::class, 'index'], 'api.v1.secure');