<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CompressionMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$response = $next($request);
		$acceptEncoding = (string)($request->headers->get('Accept-Encoding') ?? '');
		if (str_contains($acceptEncoding, 'gzip')) {
			$body = $response->getContent();
			if ($body !== false && $body !== null && ($response->headers->get('Content-Encoding') === null)) {
				$gz = gzencode($body, 6);
				$response->setContent($gz);
				$response->headers->set('Content-Encoding', 'gzip');
				$response->headers->set('Vary', 'Accept-Encoding');
			}
		}
		return $response;
	}
}