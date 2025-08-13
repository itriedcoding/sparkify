<?php

/** @var Coreon\Core\Routing\Router $router */

$router->get('/', function () {
	return '<!doctype html><html><head><title>Coreon</title></head><body><h1>Welcome to Coreon</h1></body></html>';
}, 'web.home');