<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Trimania\Core\Database;
use Trimania\Core\QueryBuilder;
use Trimania\Core\Content;
use Trimania\Core\Crawler;
use Trimania\Core\ExportData;

$date = isset($_GET['date']) ? $_GET['date'] : '';

if ($date == '') {
	echo 'Date is not defined!';
	exit;
}

//Envfile
$dotenv = new Dotenv\Dotenv(__DIR__.'/../', '.env');
$dotenv->load();

// Connection
$config = require __DIR__ . '/../config/database.php';
$database = new Database($config);

$queryBuilder = new QueryBuilder($database);

// Content Html
$content = new Content($date);
$html = $content->getHtml();

$crawler = new Crawler($html);
$dates   = $crawler->getDates();
$numbers = $crawler->getNumbers();
$locations = $crawler->getLocations();

echo $date . '<br>';

echo 'Starting numbers importation...';

foreach ($numbers as $number) {
	$numberSql = $queryBuilder
		->table('numbers')
		->columns(['draw_date', 'numbers_drawn'])
		->values([$date, $number])
		->insert();
	var_dump('number='. $number);
}	

echo '...End numbers importation';

echo '<br>'; ##############################

echo 'Starting locations importation...';

foreach ($locations as $location) {
	$locationSql = $queryBuilder
		->table('locations')
		->columns(['draw_date, city_district'])
		->values([$date, $location])
		->insert();
	var_dump('location='. $location);		
}

echo '...End locations importation';
