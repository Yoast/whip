<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Message Unit tests.
 */
final class BasicMessageTest extends TestCase {

	/**
	 * Tests if Whip_BasicMessage correctly handles a string for its body argument.
	 *
	 * @covers Whip_BasicMessage::body
	 *
	 * @return void
	 */
	public function testMessageHasBody() {
		$message = new Whip_BasicMessage( 'This is a message' );

		$this->assertNotEmpty( $message->body() );
	}

	/**
	 * Tests if an Exception is correctly thrown when an empty string is passed as argument.
	 *
	 * @covers Whip_BasicMessage::validateParameters
	 *
	 * @return void
	 */
	public function testMessageCannotBeEmpty() {
		$this->expectExceptionHelper( 'Whip_EmptyProperty', 'Message body cannot be empty.' );

		new Whip_BasicMessage( '' );
	}

	/**
	 * Tests if an Exception is correctly thrown when an invalid type is passed as argument.
	 *
	 * @covers Whip_BasicMessage::validateParameters
	 *
	 * @return void
	 */
	public function testMessageMustBeString() {
		$this->expectExceptionHelper( 'Whip_InvalidType', 'Message body should be of type string. Found integer.' );

		new Whip_BasicMessage( 123 );
	}
}
