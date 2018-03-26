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

	public function testMessageHasBody() {
		$message = new Whip_BasicMessage( 'This is a message' );

		$this->assertNotEmpty( $message->body() );
	}

	/**
	 * @expectedException Whip_EmptyProperty
	 */
	public function testMessageCannotBeEmpty() {
		new Whip_BasicMessage( '' );
	}

	/**
	 * @expectedException Whip_InvalidType
	 * @expectedExceptionMessage Message body should be of type string. Found integer.
	 */
	public function testMessageMustBeString() {
		new Whip_BasicMessage( 123 );
	}
}
