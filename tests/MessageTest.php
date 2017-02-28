<?php

class MessageTest extends PHPUnit_Framework_TestCase {
	public function testMessageHasBody() {
		$message = new Whip_BasicMessage( "This is a message" );

		$this->assertNotEmpty($message->body());
	}

	/**
	 * @expectedException EmptyProperty
	 */
	public function testMessageCannotBeEmpty() {
		new Whip_BasicMessage( "" );
	}

	/**
	 * @expectedException InvalidType
	 */
	public function testMessageMustBeString() {
		new Whip_BasicMessage( 123 );
	}
}
