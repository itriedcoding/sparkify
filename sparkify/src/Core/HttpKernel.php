<?php

declare(strict_types=1);

namespace Sparkify\Core;

use Sparkify\Core\Http\Middleware\ErrorHandlingMiddleware;
use Sparkify\Core\Http\Middleware\JsonBodyParserMiddleware;
use Sparkify\Core\Http\Middleware\RoutingMiddleware;
use Sparkify\Core\Http\Middleware\CorsMiddleware;
use Sparkify\Core\Http\Middleware\RequestIdMiddleware;
use Sparkify\Core\Http\Middleware\RequestLoggingMiddleware;
use Sparkify\Core\Http\Middleware\RateLimitMiddleware;
use Sparkify\Core\Routing\Router;
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
		$this->addMiddleware(new RequestIdMiddleware());
		$this->addMiddleware(new RateLimitMiddleware(120, 60));
		$this->addMiddleware(new RequestLoggingMiddleware($this->logger));
		$this->addMiddleware(new CorsMiddleware());
		$this->addMiddleware(new JsonBodyParserMiddleware());
		$this->addMiddleware(new RoutingMiddleware($this->router, $this->container));
	}
}