<?php

namespace Yoast\WHIP\Tests\Unit;

use Yoast\WHIP\Tests\Unit\Doubles\DismissStorageMock;
use Yoast\WHIPv2\MessageDismisser;

/**
 * Message Dismisser unit tests.
 */
final class MessageDismisserTest extends TestCase {

	/**
	 * Tests if MessageDismisser correctly updates DismissStorage.
	 *
	 * @covers \Yoast\WHIPv2\MessageDismisser::__construct
	 * @covers \Yoast\WHIPv2\MessageDismisser::dismiss
	 *
	 * @return void
	 */
	public function testDismiss() {
		$currentTime = \time();
		$storage     = new DismissStorageMock();
		$dismisser   = new MessageDismisser( $currentTime, ( \WEEK_IN_SECONDS * 4 ), $storage );

		$this->assertSame( 0, $storage->get() );

		$dismisser->dismiss();

		$this->assertSame( $currentTime, $storage->get() );
	}

	/**
	 * Tests if isDismissed correctly sets the version.
	 *
	 * @dataProvider versionNumbersProvider
	 *
	 * @covers \Yoast\WHIPv2\MessageDismisser::__construct
	 * @covers \Yoast\WHIPv2\MessageDismisser::isDismissed
	 *
	 * @param int  $savedTime   The saved time.
	 * @param int  $currentTime The current time.
	 * @param bool $expected    The expected value.
	 *
	 * @return void
	 */
	public function testIsDismissibleWithVersions( $savedTime, $currentTime, $expected ) {
		$storage = new DismissStorageMock();
		$storage->set( $savedTime );
		$dismisser = new MessageDismisser( $currentTime, ( \WEEK_IN_SECONDS * 4 ), $storage );

		$this->assertSame( $expected, $dismisser->isDismissed() );
	}

	/**
	 * Provides array with test values.
	 *
	 * @return array<string, array<int|bool>>
	 */
	public static function versionNumbersProvider() {
		return array(
			'-2weeks' => array( \strtotime( '-2weeks' ), \time(), true ),
			'-4weeks' => array( \strtotime( '-4weeks' ), \time(), true ),
			'-6weeks' => array( \strtotime( '-6weeks' ), \time(), false ),
			'time()'  => array( \time(), \time(), true ),
		);
	}
}
