<?php

declare(strict_types=1);

namespace Coreon\Core\Routing;

use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function FastRoute\simpleDispatcher;

final class Router
{
	private string $basePath;
	private Container $container;
	private ?Dispatcher $dispatcher = null;
	/** @var array<int, array{method: string, path: string, handler: mixed, name?: string}> */
	private array $routes = [];

	public function __construct(string $basePath, Container $container)
	{
		$this->basePath = rtrim($basePath, '/');
		$this->container = $container;
	}

	public function get(string $path, mixed $handler, ?string $name = null): void { $this->add('GET', $path, $handler, $name); }
	public function post(string $path, mixed $handler, ?string $name = null): void { $this->add('POST', $path, $handler, $name); }
	public function put(string $path, mixed $handler, ?string $name = null): void { $this->add('PUT', $path, $handler, $name); }
	public function patch(string $path, mixed $handler, ?string $name = null): void { $this->add('PATCH', $path, $handler, $name); }
	public function delete(string $path, mixed $handler, ?string $name = null): void { $this->add('DELETE', $path, $handler, $name); }
	public function options(string $path, mixed $handler, ?string $name = null): void { $this->add('OPTIONS', $path, $handler, $name); }

	public function add(string $method, string $path, mixed $handler, ?string $name = null): void
	{
		$this->routes[] = [
			'method' => strtoupper($method),
			'path' => $path,
			'handler' => $handler,
			'name' => $name,
		];
	}

	/** @return array<int, array{method: string, path: string, name?: string}> */
	public function list(): array
	{
		return array_map(static fn ($r) => [
			'method' => $r['method'],
			'path' => $r['path'],
			'name' => $r['name'] ?? null,
		], $this->routes);
	}

	public function dispatch(Request $request): Response
	{
		$this->ensureLoaded();
		$httpMethod = $request->getMethod();
		$uri = $request->getPathInfo();
		$result = $this->dispatcher->dispatch($httpMethod, $uri);
		switch ($result[0]) {
			case Dispatcher::NOT_FOUND:
				return new Response('Not Found', 404);
			case Dispatcher::METHOD_NOT_ALLOWED:
				$allowed = $result[1];
				return new Response('Method Not Allowed', 405, ['Allow' => implode(', ', $allowed)]);
			case Dispatcher::FOUND:
				$handler = $result[1];
				$vars = $result[2];
				return $this->invokeHandler($handler, $vars, $request);
		}

		return new Response('Internal Server Error', 500);
	}

	private function ensureLoaded(): void
	{
		if ($this->dispatcher !== null) {
			return;
		}
		$this->loadRouteFiles();
		$this->dispatcher = simpleDispatcher(function (RouteCollector $r): void {
			foreach ($this->routes as $route) {
				$r->addRoute($route['method'], $route['path'], $route['handler']);
			}
		});
	}

	private function loadRouteFiles(): void
	{
		$routesDir = $this->basePath . '/routes';
		foreach (['web.php', 'api.php'] as $file) {
			$path = $routesDir . '/' . $file;
			if (is_file($path)) {
				$router = $this; // make available to included file
				require $path;
			}
		}
	}

	private function invokeHandler(mixed $handler, array $vars, Request $request): Response
	{
		if (is_string($handler) && str_contains($handler, '@')) {
			[$class, $method] = explode('@', $handler, 2);
			$controller = $this->container->get($class);
			$result = $controller->{$method}($request, ...array_values($vars));
			return \Coreon\Core\Http\ResponseFactory::from($result);
		}
		if (is_array($handler) && is_string($handler[0])) {
			$controller = $this->container->get($handler[0]);
			$method = $handler[1];
			$result = $controller->{$method}($request, ...array_values($vars));
			return \Coreon\Core\Http\ResponseFactory::from($result);
		}
		if (is_callable($handler)) {
			$result = $handler($request, ...array_values($vars));
			return \Coreon\Core\Http\ResponseFactory::from($result);
		}
		return new Response('Bad handler', 500);
	}
}