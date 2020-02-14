<?php 

return [
	'host' => getenv('DB_HOST'),
	'user' => getenv('DB_USER'),
	'pass' => getenv('DB_PASS'),
	'port' => getenv('DB_PORT'),
	'base' => getenv('DB_NAME'),
];
