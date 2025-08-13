<?php

declare(strict_types=1);

namespace Coreon\Core\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ResponseFactory
{
	public static function from(mixed $value): Response
	{
		if ($value instanceof Response) {
			return $value;
		}
		if (is_array($value) || is_object($value)) {
			return new JsonResponse($value);
		}
		if (is_string($value)) {
			return new Response($value);
		}
		if (is_null($value)) {
			return new Response('', 204);
		}
		return new Response((string)$value);
	}
}