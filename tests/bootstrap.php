<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

if ( file_exists( dirname( __FILE__ ) . '/../vendor/autoload_52.php' ) ) {
	require_once dirname( __FILE__ ) . '/../vendor/autoload_52.php';
}
else {
	echo 'ERROR: Run `composer install` to generate the autoload files before running the unit tests.' . PHP_EOL;
	exit( 1 );
}
