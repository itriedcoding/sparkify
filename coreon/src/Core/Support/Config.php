<?php

declare(strict_types=1);

namespace Coreon\Core\Support;

final class Config
{
	/** @var array<string, mixed> */
	private static array $items = [];

	public static function load(string $configDir): void
	{
		if (!is_dir($configDir)) {
			return;
		}
		$files = glob(rtrim($configDir, '/') . '/*.php') ?: [];
		foreach ($files as $file) {
			$key = pathinfo($file, PATHINFO_FILENAME);
			$value = require $file;
			self::$items[$key] = $value;
		}
	}

	public static function get(string $key, mixed $default = null): mixed
	{
		$segments = explode('.', $key);
		$value = self::$items;
		foreach ($segments as $segment) {
			if (is_array($value) && array_key_exists($segment, $value)) {
				$value = $value[$segment];
			} else {
				return $default;
			}
		}
		return $value;
	}

	public static function set(string $key, mixed $value): void
	{
		$segments = explode('.', $key);
		$ref =& self::$items;
		foreach ($segments as $segment) {
			if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
				$ref[$segment] = [];
			}
			$ref =& $ref[$segment];
		}
		$ref = $value;
	}
}