<?php 

require_once __DIR__ . '/../vendor/autoload.php';	

use Trimania\Core\Database;
use Trimania\Core\QueryBuilder;
use Trimania\Core\ExportData;

//Envfile
$dotenv = new Dotenv\Dotenv(__DIR__.'/../', '.env');
$dotenv->load();

// Connection
$config = require __DIR__ . '/../config/database.php';
$database = new Database($config);

$queryBuilder = new QueryBuilder($database);

// $data = $queryBuilder
// 	->table('locations')
// 	->select();

// $export = new ExportData($data, 'trimaniaLocations', ';');

$data = $queryBuilder
	->table('numbers')
	->columns(['draw_date', 'numbers_drawn'])
	->where(['YEAR(draw_date) = 2018'])
	->select();

$export = new ExportData($data, 'trimaniaNumbers', ';');

$export->download();