<?php

declare(strict_types=1);

namespace Sparkify\Core\Http;

use Sparkify\Core\View\ViewManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
	protected function json(mixed $data, int $status = 200, array $headers = []): JsonResponse
	{
		return new JsonResponse($data, $status, $headers);
	}

	protected function view(ViewManager $views, string $template, array $data = [], int $status = 200, array $headers = []): Response
	{
		$html = $views->render($template, $data);
		return new Response($html, $status, array_merge(['Content-Type' => 'text/html; charset=UTF-8'], $headers));
	}
}