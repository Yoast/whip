<?php


class VersionTest extends PHPUnit_Framework_TestCase {
	public function testVersionNoFatal() {
		$version = require dirname( __FILE__ ) . '/../src/version.php';

		$this->assertEquals( WHIP_VERSION, $version );
	}
}
