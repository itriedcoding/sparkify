<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ETagMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$response = $next($request);
		if (!in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
			return $response;
		}
		$etag = sha1($response->getContent() ?? '');
		$response->setEtag($etag);
		if ($response->isNotModified($request)) {
			$response->setNotModified();
		}
		return $response;
	}
}