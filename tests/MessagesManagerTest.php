<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Message Manager unit tests.
 */
class MessagesManagerTest extends PHPUnit_Framework_TestCase {

	/**
	 * Creates a MessagesManager, calls hasMessages and tests if it returns false
	 * without a message, true when given a message.
	 *
	 * @covers Whip_MessagesManager::hasMessages
	 */
	public function testHasMessages() {
		$manager = new Whip_MessagesManager();

		$this->assertFalse( $manager->hasMessages() );

		$GLOBALS['whip_messages'][] = 'I am a test message';

		$this->assertTrue( $manager->hasMessages() );
	}
}
