<?php

use Sparkify\Core\Support\Env;

return [
	'paths' => [__DIR__ . '/../resources/views'],
	'cache' => Env::bool('VIEW_CACHE', false) ? (__DIR__ . '/../storage/cache/views') : false,
];