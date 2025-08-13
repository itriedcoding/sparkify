<?php

use Coreon\Core\Support\Env;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Monolog\Logger;

return [
	Connection::class => static function (): Connection {
		$url = Env::get('DATABASE_URL');
		$params = $url ? ['url' => $url] : [
			'driver' => Env::get('DB_DRIVER', 'pdo_sqlite'),
			'path' => Env::get('DB_PATH', __DIR__ . '/../storage/database.sqlite'),
		];
		return DriverManager::getConnection($params);
	},
	LoggerInterface::class => static function ($container) {
		return $container->get(Logger::class);
	},
];