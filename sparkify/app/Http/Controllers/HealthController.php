<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Sparkify\Core\Support\Config;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use Psr\SimpleCache\CacheInterface;

final class HealthController
{
	private Connection $db;
	private CacheInterface $cache;

	public function __construct(Connection $db, CacheInterface $cache)
	{
		$this->db = $db;
		$this->cache = $cache;
	}

	public function index(Request $request): array
	{
		$dbOk = true;
		$cacheOk = true;
		try { $this->db->executeQuery('SELECT 1'); } catch (\Throwable $e) { $dbOk = false; }
		try { $this->cache->set('health_ping', 'ok', 5); $cacheOk = $this->cache->get('health_ping') === 'ok'; } catch (\Throwable $e) { $cacheOk = false; }

		return [
			'status' => ($dbOk && $cacheOk) ? 'ok' : 'degraded',
			'app' => [
				'name' => (string)Config::get('app.name', 'Sparkify'),
				'env' => (string)Config::get('app.env', 'local'),
				'debug' => (bool)Config::get('app.debug', false),
			],
			'checks' => [
				'db' => $dbOk,
				'cache' => $cacheOk,
			],
			'php' => PHP_VERSION,
			'time' => gmdate('c'),
		];
	}

	public function metrics(): array
	{
		return [
			'uptime_seconds' => (int)(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']),
			'memory_usage_bytes' => memory_get_usage(true),
		];
	}
}