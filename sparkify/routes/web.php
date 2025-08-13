<?php

/** @var Sparkify\Core\Routing\Router $router */

$router->get('/', function () {
	return '<!doctype html><html><head><title>Sparkify</title></head><body><h1>Welcome to Sparkify</h1></body></html>';
}, 'web.home');