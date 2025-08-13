<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestLoggingMiddleware
{
	private Logger $logger;

	public function __construct(Logger $logger)
	{
		$this->logger = $logger;
	}

	public function __invoke(Request $request, callable $next): Response
	{
		$start = microtime(true);
		$response = $next($request);
		$durationMs = (int)round((microtime(true) - $start) * 1000);
		$requestId = (string)($request->headers->get('X-Request-Id') ?? '');
		$this->logger->info('HTTP', [
			'method' => $request->getMethod(),
			'path' => $request->getPathInfo(),
			'status' => $response->getStatusCode(),
			'duration_ms' => $durationMs,
			'request_id' => $requestId,
		]);
		return $response;
	}
}