<?php

namespace Yoast\WHIP\Tests\Unit;

use Yoast\WHIPv2\Messages\BasicMessage;

/**
 * Message Unit tests.
 */
final class BasicMessageTest extends TestCase {

	/**
	 * Tests if BasicMessage correctly handles a string for its body argument.
	 *
	 * @covers \Yoast\WHIPv2\Messages\BasicMessage::body
	 *
	 * @return void
	 */
	public function testMessageHasBody() {
		$message = new BasicMessage( 'This is a message' );

		$this->assertNotEmpty( $message->body() );
	}

	/**
	 * Tests if an Exception is correctly thrown when an empty string is passed as argument.
	 *
	 * @covers \Yoast\WHIPv2\Messages\BasicMessage::validateParameters
	 *
	 * @return void
	 */
	public function testMessageCannotBeEmpty() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\EmptyProperty', 'Message body cannot be empty.' );

		new BasicMessage( '' );
	}

	/**
	 * Tests if an Exception is correctly thrown when an invalid type is passed as argument.
	 *
	 * @covers \Yoast\WHIPv2\Messages\BasicMessage::validateParameters
	 *
	 * @return void
	 */
	public function testMessageMustBeString() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\InvalidType', 'Message body should be of type string. Found integer.' );

		new BasicMessage( 123 );
	}
}
