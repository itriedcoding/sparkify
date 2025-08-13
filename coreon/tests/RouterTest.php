<?php

declare(strict_types=1);

use Coreon\Core\Routing\Router;
use DI\Container;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
	public function testRoutesLoad(): void
	{
		$container = new Container();
		$router = new Router(__DIR__ . '/..', $container);
		$reflection = new ReflectionMethod($router, 'list');
		$routes = $router->list();
		$this->assertIsArray($routes);
	}
}