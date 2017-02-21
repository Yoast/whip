<?php


class PHPVersionDetectorTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->subject = new Whip_PHPVersionDetector( '' );
	}

	public function testDetect() {
		$phpVersionDetector = new Whip_PHPVersionDetector( '' );

		$this->assertEquals( phpversion(), $phpVersionDetector->detect() );
	}
}
