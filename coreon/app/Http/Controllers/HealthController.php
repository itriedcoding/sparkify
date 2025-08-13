<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Sparkify\Core\Support\Config;
use Symfony\Component\HttpFoundation\Request;

final class HealthController
{
	public function index(Request $request): array
	{
		return [
			'status' => 'ok',
			'app' => [
				'name' => (string)Config::get('app.name', 'Sparkify'),
				'env' => (string)Config::get('app.env', 'local'),
				'debug' => (bool)Config::get('app.debug', false),
			],
			'php' => PHP_VERSION,
			'time' => gmdate('c'),
		];
	}
}