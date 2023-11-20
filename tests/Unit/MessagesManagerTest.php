<?php

namespace Yoast\WHIP\Tests\Unit;

use Whip_MessagesManager;

/**
 * Message Manager unit tests.
 */
final class MessagesManagerTest extends TestCase {

	/**
	 * Creates a MessagesManager, calls hasMessages and tests if it returns false
	 * without a message, true when given a message.
	 *
	 * @covers Whip_MessagesManager::hasMessages
	 *
	 * @return void
	 */
	public function testHasMessages() {
		$manager = new Whip_MessagesManager();

		$this->assertFalse( $manager->hasMessages() );

		$GLOBALS['whip_messages'][] = 'I am a test message';

		$this->assertTrue( $manager->hasMessages() );
	}
}
