<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RateLimitMiddleware
{
	private int $limit;
	private int $windowSeconds;
	/** @var array<string, array{count:int, reset:int}> */
	private static array $buckets = [];

	public function __construct(int $limit = 60, int $windowSeconds = 60)
	{
		$this->limit = $limit;
		$this->windowSeconds = $windowSeconds;
	}

	public function __invoke(Request $request, callable $next): Response
	{
		$key = $this->keyFor($request);
		$bucket = self::$buckets[$key] ?? ['count' => 0, 'reset' => time() + $this->windowSeconds];
		if (time() > $bucket['reset']) {
			$bucket = ['count' => 0, 'reset' => time() + $this->windowSeconds];
		}
		$bucket['count']++;
		self::$buckets[$key] = $bucket;

		$remaining = max(0, $this->limit - $bucket['count']);
		$retryAfter = max(0, $bucket['reset'] - time());

		if ($bucket['count'] > $this->limit) {
			$response = new Response('Too Many Requests', 429);
			$response->headers->set('Retry-After', (string)$retryAfter);
			$response->headers->set('X-RateLimit-Limit', (string)$this->limit);
			$response->headers->set('X-RateLimit-Remaining', (string)$remaining);
			$response->headers->set('X-RateLimit-Reset', (string)$bucket['reset']);
			return $response;
		}

		$response = $next($request);
		$response->headers->set('X-RateLimit-Limit', (string)$this->limit);
		$response->headers->set('X-RateLimit-Remaining', (string)$remaining);
		$response->headers->set('X-RateLimit-Reset', (string)$bucket['reset']);
		return $response;
	}

	private function keyFor(Request $request): string
	{
		$ip = $request->getClientIp() ?? 'unknown';
		return sha1($ip . '|' . $request->getMethod());
	}
}