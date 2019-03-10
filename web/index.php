<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Trimania\Application;
use Pimple\Container;

use Trimania\Entity\Repository\LocationRepository;

$container = new Container();

$app = new Application($container);
$app->register(new Trimania\Core\Provider\RouterProvider());
$app->register(new Trimania\Core\Provider\DatabaseProvider());
$app->register(new Trimania\Core\Provider\TwigProvider());

$app->get('/hello/(\w+)', function($name) use ($container) {
	
	$repository = new LocationRepository($container['database']);

	$data = $repository->all();

	var_dump($data);
});
	
$app->run();