<?php
	require_once("../libraries/scssphp-0.6.7/scss.inc.php");
	use Leafo\ScssPhp\Compiler;
	use Leafo\ScssPhp\Server;

	// Enable gzip compression of output
	ob_start("ob_gzhandler");

	// Create compiler
	$scss = new Compiler();
	if (array_key_exists("min", $_GET))
		$scss->setFormatter("Leafo\ScssPhp\Formatter\Crunched");

	// Start server
	$directory = ".";
	$server = new Server($directory, null, $scss);
	$server->serve();
?>