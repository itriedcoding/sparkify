<?php

declare(strict_types=1);

use Sparkify\Core\Application;

return static function (string $basePath): Application {
	return new Application($basePath);
};