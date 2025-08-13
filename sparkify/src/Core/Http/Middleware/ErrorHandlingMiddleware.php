<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Sparkify\Core\Support\Config;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ErrorHandlingMiddleware
{
	private Logger $logger;

	public function __construct($container)
	{
		$this->logger = $container->get(Logger::class);
	}

	public function __invoke(Request $request, callable $next): Response
	{
		try {
			return $next($request);
		} catch (Throwable $e) {
			$this->logger->error('Unhandled exception', [
				'exception' => $e,
				'path' => $request->getPathInfo(),
			]);
			$debug = (bool)Config::get('app.debug', false);
			if ($debug) {
				$whoops = new \Whoops\Run();
				$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
				$content = $whoops->handleException($e);
				return new Response($content, 500, ['Content-Type' => 'text/html; charset=UTF-8']);
			}
			return new Response('Internal Server Error', 500);
		}
	}
}