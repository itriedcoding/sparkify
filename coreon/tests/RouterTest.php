<?php

declare(strict_types=1);

use Sparkify\Core\Routing\Router;
use DI\Container;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
	public function testRoutesLoad(): void
	{
		$container = new Container();
		$router = new Router(__DIR__ . '/..', $container);
		$routes = $router->list();
		$this->assertIsArray($routes);
	}
}