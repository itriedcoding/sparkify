<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestIdMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$requestId = $request->headers->get('X-Request-Id') ?? bin2hex(random_bytes(8));
		$request->headers->set('X-Request-Id', $requestId);
		$response = $next($request);
		$response->headers->set('X-Request-Id', $requestId);
		return $response;
	}
}