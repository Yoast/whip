<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Message Dismisser unit tests.
 */
class MessageDismisserTest extends Whip_TestCase {

	/**
	 * Tests if Whip_MessageDismisser correctly updates Whip_DismissStorage.
	 *
	 * @covers Whip_MessageDismisser::__construct
	 * @covers Whip_MessageDismisser::dismiss
	 */
	public function testDismiss() {
		$currentTime = time();
		$storage     = new Whip_DismissStorageMock();
		$dismisser   = new Whip_MessageDismisser( $currentTime, ( WEEK_IN_SECONDS * 4 ), $storage );

		$this->assertSame( 0, $storage->get() );

		$dismisser->dismiss();

		$this->assertSame( $currentTime, $storage->get() );
	}

	/**
	 * Tests if isDismissed correctly sets the version.
	 *
	 * @dataProvider versionNumbersProvider
	 *
	 * @covers Whip_MessageDismisser::__construct
	 * @covers Whip_MessageDismisser::isDismissed
	 *
	 * @param int  $savedTime   The saved time.
	 * @param int  $currentTime The current time.
	 * @param bool $expected    The expected value.
	 */
	public function testIsDismissibleWithVersions( $savedTime, $currentTime, $expected ) {
		$storage = new Whip_DismissStorageMock();
		$storage->set( $savedTime );
		$dismisser = new Whip_MessageDismisser( $currentTime, ( WEEK_IN_SECONDS * 4 ), $storage );

		$this->assertSame( $expected, $dismisser->isDismissed() );
	}

	/**
	 * Provides array with test values.
	 *
	 * @return array[]
	 */
	public function versionNumbersProvider() {
		return array(
			array( strtotime( '-2weeks' ), time(), true ),
			array( strtotime( '-4weeks' ), time(), true ),
			array( strtotime( '-6weeks' ), time(), false ),
			array( time(), time(), true ),
		);
	}
}
