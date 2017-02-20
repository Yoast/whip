<?php


class VersionMessageControlTest extends PHPUnit_Framework_TestCase {

	public function testConstructor() {
		$versionDetector = new PHPVersionDetector();

		$versionMessageControl = new VersionMessageControl(
			new PHPVersionDetector(),
			array()
		);

		$this->assertEquals( $versionDetector, $versionMessageControl->getVersionDetector() );
		$this->assertEquals( array(), $versionMessageControl->getMessagePresenters() );
	}
}
