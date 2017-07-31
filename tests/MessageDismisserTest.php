<?php

class Whip_DismissStorageMock implements Whip_DismissStorage {

	/** @var string  */
	protected $dismissed = '';

	/**
	 * Saves the value.
	 *
	 * @param string $dismissedVersion The value to save.
	 */
	public function set( $dismissedVersion ) {
		$this->dismissed = $dismissedVersion;
	}

	/**
	 * Returns the value.
	 *
	 * @return string
	 */
	public function get() {
		return $this->dismissed;
	}
}

class MessageDismisserTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers Whip_MessageDismisser::__construct()
	 * @covers Whip_MessageDismisser::dismiss()
	 */
	public function testDismiss() {
		$storage = new Whip_DismissStorageMock();
		$dismisser = new Whip_MessageDismisser( '4.8', $storage );

		$dismisser->dismiss();

		$this->assertEquals( '4.8' , $storage->get() );
	}

	/**
	 * @dataProvider versionNumbersProvider
	 *
	 * @param string $savedVersion   The saved version number.
	 * @param string $currentVersion The current version number.
	 * @param bool   $expected       The expected value.
	 *
	 * @covers Whip_MessageDismisser::__construct()
	 * @covers Whip_MessageDismisser::isDismissed()
	 * @covers Whip_MessageDismisser::toMajorVersion()
	 */
	public function testIsDismissibleWithVersions( $savedVersion, $currentVersion, $expected ) {
		$storage = new Whip_DismissStorageMock();
		$storage->set( $savedVersion );
		$dismisser = new Whip_MessageDismisser( $currentVersion, $storage );

		$this->assertEquals( $expected, $dismisser->isDismissed() );
	}

	public function versionNumbersProvider() {
		return array(
			array( '4.8', '4.8', true ),
			array( '4.8', '4.8.1', true ),
			array( '4.7', '4.8', false ),
			array( '4.7', '4.8.1', false ),
			array( '4.7.1', '4.8.1', false ),
			array( '4.7', '4.7-alpha', true ),
		);
	}

}
