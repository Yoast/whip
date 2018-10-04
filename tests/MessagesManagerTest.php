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
	 * Creates a MessagesManager, tests if it returns false without message, true when given a message.
	 */
	public function testHasMessages() {
		$manager = new Whip_MessagesManager();

		$this->assertFalse( $manager->hasMessages() );

		$GLOBALS['whip_messages'][] = 'I am a test message';

		$this->assertTrue( $manager->hasMessages() );
	}
}
