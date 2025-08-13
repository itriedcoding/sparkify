<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class JsonBodyParserMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$contentType = (string)($request->headers->get('Content-Type') ?? '');
		if ($request->getContentLength() > 0 && str_contains(strtolower($contentType), 'application/json')) {
			$raw = (string)$request->getContent();
			if ($raw !== '') {
				$data = json_decode($raw, true);
				if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
					$request->request->replace($data);
				}
			}
		}
		return $next($request);
	}
}