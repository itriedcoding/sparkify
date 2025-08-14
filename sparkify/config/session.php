<?php

use Sparkify\Core\Support\Env;

return [
	'name' => (string)Env::get('SESSION_NAME', 'sparkify_session'),
	'lifetime' => (int)Env::get('SESSION_LIFETIME', 7200),
	'cookie_secure' => Env::bool('SESSION_SECURE', false),
	'cookie_httponly' => true,
	'cookie_samesite' => (string)Env::get('SESSION_SAMESITE', 'Lax'),
];