<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Trimania\Command\ImportCommand;

$application = new Application();
$application->add(new ImportCommand());

$application->run();