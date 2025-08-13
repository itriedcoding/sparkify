<?php

declare(strict_types=1);

namespace Sparkify\Core\Support;

use Dotenv\Dotenv;

final class Env
{
	public static function load(string $envFilePath): void
	{
		$dir = dirname($envFilePath);
		if (!is_dir($dir)) {
			return;
		}
		if (!is_file($envFilePath)) {
			return;
		}
		$dotenv = Dotenv::createImmutable($dir, basename($envFilePath));
		$dotenv->safeLoad();
	}

	public static function get(string $key, mixed $default = null): mixed
	{
		if (array_key_exists($key, $_ENV)) {
			return $_ENV[$key];
		}
		$value = getenv($key);
		return $value === false ? $default : $value;
	}

	public static function bool(string $key, bool $default = false): bool
	{
		$value = self::get($key);
		if ($value === null) {
			return $default;
		}
		$value = strtolower((string)$value);
		return in_array($value, ['1', 'true', 'yes', 'on'], true);
	}
}