<?php


class PHPVersionDetectorTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->subject = new PHPVersionDetector( '' );
	}

	public function testDetect() {
		$phpVersionDetector = new PHPVersionDetector( '' );

		$this->assertEquals( phpversion(), $phpVersionDetector->detect() );
	}
}
