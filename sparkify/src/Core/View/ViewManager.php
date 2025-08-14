<?php

declare(strict_types=1);

namespace Sparkify\Core\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Sparkify\Core\Support\Config;

final class ViewManager
{
	private Environment $twig;

	public function __construct()
	{
		$paths = (array)Config::get('view.paths', []);
		$cache = Config::get('view.cache', false);
		$loader = new FilesystemLoader($paths);
		$this->twig = new Environment($loader, [
			'cache' => $cache ?: false,
			'autoescape' => 'html',
		]);
	}

	public function render(string $template, array $data = []): string
	{
		return $this->twig->render($template, $data);
	}

	public function getTwig(): Environment
	{
		return $this->twig;
	}
}