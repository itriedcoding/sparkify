<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Sparkify\Core\Routing\Router;
use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RoutingMiddleware
{
	private Router $router;
	private Container $container;

	public function __construct(Router $router, Container $container)
	{
		$this->router = $router;
		$this->container = $container;
	}

	public function __invoke(Request $request, callable $next): Response
	{
		$response = $this->router->dispatch($request);
		if ($response->getStatusCode() === 404 || $response->getStatusCode() === 405) {
			return $next($request);
		}
		return $response;
	}
}