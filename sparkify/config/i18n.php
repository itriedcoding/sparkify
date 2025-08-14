<?php

use Sparkify\Core\Support\Env;

return [
	'locale' => (string)Env::get('APP_LOCALE', 'en'),
	'paths' => [__DIR__ . '/../resources/lang'],
];