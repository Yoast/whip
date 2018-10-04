<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Message Unit tests.
 */
class MessageTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests if Whip_BasicMessage correctly handles a string for its body argument.
	 */
	public function testMessageHasBody() {
		$message = new Whip_BasicMessage( 'This is a message' );

		$this->assertNotEmpty( $message->body() );
	}

	/**
	 * Tests if an Exception is correctly thrown when an empty string is passed as argument.
	 *
	 * @expectedException Whip_EmptyProperty
	 * @expectedExceptionMessage Message body cannot be empty.
	 */
	public function testMessageCannotBeEmpty() {
		new Whip_BasicMessage( '' );
	}

	/**
	 * Tests if an Exception is correctly thrown when an invalid type is passed as argument.
	 *
	 * @expectedException Whip_InvalidType
	 * @expectedExceptionMessage Message body should be of type string. Found integer.
	 */
	public function testMessageMustBeString() {
		new Whip_BasicMessage( 123 );
	}
}
