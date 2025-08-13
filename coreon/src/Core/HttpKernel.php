<?php

declare(strict_types=1);

namespace Coreon\Core;

use Coreon\Core\Http\Middleware\ErrorHandlingMiddleware;
use Coreon\Core\Http\Middleware\JsonBodyParserMiddleware;
use Coreon\Core\Http\Middleware\RoutingMiddleware;
use Coreon\Core\Http\Middleware\CorsMiddleware;
use Coreon\Core\Routing\Router;
use DI\Container;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HttpKernel
{
	private Container $container;
	private string $basePath;
	private Router $router;
	private Logger $logger;

	/** @var array<int, callable> */
	private array $middlewareStack = [];

	public function __construct(Container $container, string $basePath)
	{
		$this->container = $container;
		$this->basePath = $basePath;
		$this->router = new Router($basePath, $container);
		$this->logger = $container->get(Logger::class);

		$this->registerCoreMiddleware();
	}

	public function handle(Request $request): Response
	{
		$handler = array_reduce(
			array_reverse($this->middlewareStack),
			fn (callable $next, callable $middleware) => fn (Request $req) => $middleware($req, $next),
			fn (Request $req) => new Response('Not Found', 404)
		);

		return $handler($request);
	}

	public function terminate(Request $request, Response $response): void
	{
		// Placeholder for terminating logic (logging, events, etc.)
		$this->logger->debug('Request terminated', [
			'status' => $response->getStatusCode(),
			'path' => $request->getPathInfo(),
		]);
	}

	public function addMiddleware(callable $middleware): void
	{
		$this->middlewareStack[] = $middleware;
	}

	private function registerCoreMiddleware(): void
	{
		$this->addMiddleware(new ErrorHandlingMiddleware($this->container));
		$this->addMiddleware(new CorsMiddleware());
		$this->addMiddleware(new JsonBodyParserMiddleware());
		$this->addMiddleware(new RoutingMiddleware($this->router, $this->container));
	}
}