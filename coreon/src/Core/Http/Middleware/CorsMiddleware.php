<?php

declare(strict_types=1);

namespace Coreon\Core\Http\Middleware;

use Coreon\Core\Support\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CorsMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$config = (array)Config::get('app.cors', []);
		$allowedOrigins = $config['allowed_origins'] ?? ['*'];
		$origin = (string)($request->headers->get('Origin') ?? '');
		$allowOrigin = in_array('*', $allowedOrigins, true) || in_array($origin, $allowedOrigins, true) ? ($origin ?: '*') : '';

		if ($request->getMethod() === 'OPTIONS') {
			$response = new Response('', 204);
			return $this->applyHeaders($response, $config, $allowOrigin);
		}

		$response = $next($request);
		return $this->applyHeaders($response, $config, $allowOrigin);
	}

	private function applyHeaders(Response $response, array $config, string $allowOrigin): Response
	{
		if ($allowOrigin !== '') {
			$response->headers->set('Access-Control-Allow-Origin', $allowOrigin);
		}
		$response->headers->set('Vary', 'Origin');
		$response->headers->set('Access-Control-Allow-Methods', implode(', ', $config['allowed_methods'] ?? ['GET','POST']));
		$response->headers->set('Access-Control-Allow-Headers', implode(', ', $config['allowed_headers'] ?? ['Content-Type','Authorization']));
		$response->headers->set('Access-Control-Expose-Headers', implode(', ', $config['exposed_headers'] ?? []));
		$response->headers->set('Access-Control-Max-Age', (string)($config['max_age'] ?? 86400));
		if (!empty($config['allow_credentials'])) {
			$response->headers->set('Access-Control-Allow-Credentials', 'true');
		}
		return $response;
	}
}