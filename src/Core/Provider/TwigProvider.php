<?php 

namespace Trimania\Core\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Trimania\Core\Router;

class TwigProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$loader = new \Twig_Loader_Filesystem([
			__DIR__ . '/../../Resources/views'
		]);

		$container['twig'] = new \Twig_Environment($loader);	
	}
}