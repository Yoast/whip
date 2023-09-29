<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	require_once __DIR__ . '/../vendor/autoload.php';
}
else {
	echo 'ERROR: Run `composer install` to generate the autoload files before running the unit tests.' . PHP_EOL;
	exit( 1 );
}

require_once __DIR__ . '/doubles/WPCoreFunctionsMock.php';
