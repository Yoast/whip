<?php


class PHPVersionDetectorTest extends PHPUnit_Framework_TestCase {

	public function testDetect() {
		$phpVersionDetector = new PHPVersionDetector();

		$this->assertEquals( phpversion(), $phpVersionDetector->detect() );
	}
}
