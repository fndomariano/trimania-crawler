<?php 

namespace Trimania\Core\Provider;

use Trimania\Core\Database;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$config = [
			'host' => getenv('DB_HOST'),
			'user' => getenv('DB_USER'),
			'pass' => getenv('DB_PASS'),
			'port' => getenv('DB_PORT'),
			'base' => getenv('DB_BASE'),
		];

		$container['database'] = new Database($config);
	}
}