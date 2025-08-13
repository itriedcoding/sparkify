<?php

use Sparkify\Core\Support\Env;

return [
	'jwt' => [
		'issuer' => (string)Env::get('JWT_ISSUER', 'sparkify'),
		'audience' => (string)Env::get('JWT_AUDIENCE', 'sparkify-clients'),
		'ttl' => (int)Env::get('JWT_TTL', 3600),
		'alg' => (string)Env::get('JWT_ALG', 'HS256'),
		'secret' => (string)Env::get('JWT_SECRET', ''),
	],
];