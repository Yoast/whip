<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/*
 * PHPUnit cross version compatibility.
 *
 * The classes in PHPUnit have been renamed to namespaced classes.
 * The namespaced alias for the TestCase class is available as of PHPUnit 5.4 and as of
 * PHPUnit 6.0, the non-namespaced versions are no longer available.
 * As PHPUnit 6.0 requires PHP 7 anyway, the PHP 5.3 function class_alias() will be available,
 * so we can use it to alias the class back to the old class name.
 */
if ( class_exists( 'PHPUnit\Framework\TestCase' ) === true
	&& class_exists( 'PHPUnit_Framework_TestCase' ) === false
) {
	// phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.class_aliasFound
	class_alias( 'PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase' );
}

if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	require_once __DIR__ . '/../vendor/autoload.php';
}
else {
	echo 'ERROR: Run `composer install` to generate the autoload files before running the unit tests.' . PHP_EOL;
	exit( 1 );
}

require_once __DIR__ . '/doubles/WPCoreFunctionsMock.php';
