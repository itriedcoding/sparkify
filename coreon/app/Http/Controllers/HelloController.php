<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

final class HelloController
{
	public function greet(Request $request, string $name): array
	{
		return [
			'message' => sprintf('Hello, %s!', $name),
			'client_ip' => $request->getClientIp(),
		];
	}
}