<?php

declare(strict_types=1);

namespace Coreon\Core;

use Coreon\Core\Support\Config;
use Coreon\Core\Support\Env;
use Coreon\Core\Support\Paths;
use DI\Container;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

final class Application
{
	private string $basePath;
	private Container $container;
	private HttpKernel $httpKernel;

	public function __construct(string $basePath)
	{
		$this->basePath = rtrim($basePath, '/');

		Env::load($this->path('.env'));
		Config::load($this->path('config'));
		@date_default_timezone_set((string)Config::get('app.timezone', 'UTC'));

		$this->container = $this->buildContainer();
		$this->httpKernel = new HttpKernel($this->container, $this->basePath);
	}

	public function getBasePath(): string
	{
		return $this->basePath;
	}

	public function container(): Container
	{
		return $this->container;
	}

	public function getHttpKernel(): HttpKernel
	{
		return $this->httpKernel;
	}

	public function path(string $relative): string
	{
		return $this->basePath . '/' . ltrim($relative, '/');
	}

	private function buildContainer(): Container
	{
		$builder = new ContainerBuilder();
		$definitionsFile = $this->path('config/container.php');
		if (is_file($definitionsFile)) {
			$builder->addDefinitions($definitionsFile);
		}

		$container = $builder->build();

		$container->set(Logger::class, $this->createLogger());

		return $container;
	}

	private function createLogger(): Logger
	{
		$logPath = $this->path('storage/logs/coreon.log');
		if (!is_dir(dirname($logPath))) {
			mkdir(dirname($logPath), 0777, true);
		}
		$logger = new Logger('coreon');
		$logger->pushHandler(new StreamHandler($logPath, Level::Debug));
		return $logger;
	}
}