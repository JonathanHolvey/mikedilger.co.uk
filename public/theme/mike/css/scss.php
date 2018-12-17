<?php declare(strict_types=1);

require __DIR__ . '/../../../../vendor/autoload.php';
require __DIR__ . '/../../../../vendor/leafo/scssphp/example/Server.php';
use Leafo\ScssPhp;

// Enable gzip compression of output
ob_start('ob_gzhandler');

// Create compiler
$scss = new ScssPhp\Compiler();
if (array_key_exists('min', $_GET))
	$scss->setFormatter('Leafo\ScssPhp\Formatter\Crunched');

// Start server
$directory = '.';
$server = new ScssPhp\Server($directory, null, $scss);
$server->serve();
