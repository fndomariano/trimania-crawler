<?php 

namespace Trimania\Core\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Trimania\Core\Router;

class RouterProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['router'] = new Router();	
	}
}