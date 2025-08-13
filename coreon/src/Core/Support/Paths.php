<?php

declare(strict_types=1);

namespace Sparkify\Core\Support;

final class Paths
{
	public static function join(string ...$segments): string
	{
		$trimmed = array_map(fn ($s) => trim($s, '/'), $segments);
		return implode('/', $trimmed);
	}
}