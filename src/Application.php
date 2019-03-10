<?php 

namespace Trimania;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Application
{
	private $container;
	
	public function __construct(Container $container)
	{
		$fileEnv = __DIR__.'/../.env';
        
        if (!file_exists($fileEnv)) {
            throw new \Exception(sprintf('The file %s not exists', $fileEnv));
        }
        
        $dotenv = new \Dotenv\Dotenv(__DIR__.'/../', '.env');
		$dotenv->load();

        $this->container = $container;
	}

	public function register($provider)
    {
        if (!$provider instanceof ServiceProviderInterface) {
	        throw new \Exception(sprintf('The class not is instance of %s', ServiceProviderInterface::class));
        }

        $this->container->register($provider);
    }

    public function run()
    {
    	$this->container['router']->run();
    }

    public function get($path, $callback)
    {
        $this->container['router']->on('get', $path, $callback);
    }   
    
    public function post($path, $callback)
    {;
        $this->container['router']->on('post', $path, $callback);
    } 
}