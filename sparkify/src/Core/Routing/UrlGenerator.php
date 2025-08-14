<?php

declare(strict_types=1);

namespace Sparkify\Core\Routing;

final class UrlGenerator
{
	/** @var array<string, string> */
	private array $nameToPath = [];

	/** @param array<int, array{method:string, path:string, name?:string}> $routes */
	public function __construct(array $routes)
	{
		foreach ($routes as $r) {
			if (!empty($r['name'])) {
				$this->nameToPath[$r['name']] = $r['path'];
			}
		}
	}

	public function route(string $name, array $params = []): string
	{
		$path = $this->nameToPath[$name] ?? '';
		foreach ($params as $key => $value) {
			$path = preg_replace('/\{' . preg_quote((string)$key, '/') . '\}/', (string)$value, $path);
		}
		return $path;
	}
}