<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Sparkify\Core\Support\Config;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class JwtAuthMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$auth = (string)($request->headers->get('Authorization') ?? '');
		if (!str_starts_with($auth, 'Bearer ')) {
			return new JsonResponse(['error' => 'Unauthorized'], 401);
		}
		$token = substr($auth, 7);
		try {
			$cfg = Config::get('auth.jwt');
			$alg = $cfg['alg'] ?? 'HS256';
			$secret = (string)($cfg['secret'] ?? '');
			$decoded = JWT::decode($token, new Key($secret, $alg));
			$request->attributes->set('auth', $decoded);
			return $next($request);
		} catch (\Throwable $e) {
			return new JsonResponse(['error' => 'Unauthorized'], 401);
		}
	}
}