<?php

use Sparkify\Core\Support\Env;

return [
	'dsn' => (string)Env::get('MAILER_DSN', 'smtp://localhost'),
	'from' => (string)Env::get('MAIL_FROM', ''),
];