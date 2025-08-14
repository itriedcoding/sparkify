<?php

declare(strict_types=1);

namespace Sparkify\Core\Http\Middleware;

use Sparkify\Core\Support\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;

final class SessionMiddleware
{
	public function __invoke(Request $request, callable $next): Response
	{
		$cfg = (array)Config::get('session', []);
		$savePath = sys_get_temp_dir() . '/sparkify_sessions';
		if (!is_dir($savePath)) { @mkdir($savePath, 0777, true); }
		$handler = new NativeFileSessionHandler($savePath);
		$storage = new NativeSessionStorage([
			'cookie_secure' => (bool)($cfg['cookie_secure'] ?? false),
			'cookie_httponly' => (bool)($cfg['cookie_httponly'] ?? true),
			'cookie_samesite' => (string)($cfg['cookie_samesite'] ?? 'Lax'),
			'name' => (string)($cfg['name'] ?? 'sparkify_session'),
			'gc_maxlifetime' => (int)($cfg['lifetime'] ?? 7200),
		], $handler);
		$session = new Session($storage);
		$request->setSession($session);
		$session->start();
		$response = $next($request);
		$session->save();
		return $response;
	}
}