<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CsrfMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$session = $request->getSession();
		if ($session) {
			if (!$session->has('csrf_token')) {
				$session->set('csrf_token', bin2hex(random_bytes(16)));
			}
		}
		if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
			$token = (string)($request->headers->get('X-CSRF-Token') ?? '');
			$valid = $session && hash_equals((string)$session->get('csrf_token'), $token);
			if (!$valid) {
				return new JsonResponse(['error' => 'CSRF token mismatch'], 419);
			}
		}
		return $next($request);
	}
}