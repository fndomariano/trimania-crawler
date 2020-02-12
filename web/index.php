<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Trimania\Command\ImportCommand;
use Trimania\Core\Database;
use Trimania\Core\QueryBuilder;


$dotenv = new Dotenv\Dotenv(__DIR__.'/../', '.env');
$dotenv->load();

$config = require __DIR__ . '/../config/database.php';
$database = new Database($config);
$queryBuilder = new QueryBuilder($database);

$application = new Application();
$application->add(new ImportCommand($queryBuilder));

$application->run();