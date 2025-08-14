<?php

use Sparkify\Core\Support\Env;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Sparkify\Core\View\ViewManager;
use Sparkify\Core\Mail\Mailer as SparkifyMailer;
use Sparkify\Core\Support\Config;

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
	CacheInterface::class => static function () {
		$pool = new ArrayAdapter();
		return new Psr16Cache($pool);
	},
	ViewManager::class => static function () {
		return new ViewManager();
	},
	SparkifyMailer::class => static function () {
		$dsn = (string)Config::get('mail.dsn', 'smtp://localhost');
		return new SparkifyMailer($dsn);
	},
];