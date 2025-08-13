<?php

use Coreon\Core\Support\Env;

return [
	'name' => Env::get('APP_NAME', 'Coreon'),
	'env' => Env::get('APP_ENV', 'local'),
	'debug' => Env::bool('APP_DEBUG', true),
	'timezone' => Env::get('APP_TIMEZONE', 'UTC'),
	'url' => Env::get('APP_URL', 'http://localhost:8000'),
	'cors' => [
		'allowed_origins' => explode(',', (string)Env::get('CORS_ALLOWED_ORIGINS', '*')),
		'allowed_methods' => ['GET','POST','PUT','PATCH','DELETE','OPTIONS'],
		'allowed_headers' => ['Content-Type','Authorization','Accept','X-Requested-With'],
		'exposed_headers' => ['Link'],
		'allow_credentials' => false,
		'max_age' => 86400,
	],
];