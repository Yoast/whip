<?php

class Whip_DismissStorageMock implements Whip_DismissStorage {

	protected $dismissed = '';

	/**
	 * Saves the value.
	 *
	 * @param string $dismissedVersion The value to save.
	 */
	public function set( $dismissedVersion ) {
		$this->dismissed = $dismissedVersion;
	}

	/**
	 * Returns the value.
	 *
	 * @return string
	 */
	public function get() {
		return $this->dismissed;
	}
}


class MessageDismisserTest extends PHPUnit_Framework_TestCase {

	public function testDismiss() {
		$storage = new Whip_DismissStorageMock();
		$dismisser = new Whip_MessageDismisser( '4.8', $storage );

		$dismisser->dismiss( 'php' );

		$this->assertEquals( '4.8' , $storage->get() );
	}

	public function testIsDismissed() {
		$storage = new Whip_DismissStorageMock();
		$storage->set( '4.7' );

		$dismisser = new Whip_MessageDismisser( '4.8', $storage );

		$this->assertTrue( $dismisser->isDismissible() );
	}

	public function testIsDismissibleNotNeeded() {
		$storage = new Whip_DismissStorageMock();
		$storage->set( '4.8' );
		$dismisser = new Whip_MessageDismisser( '4.8', $storage );

		$this->assertFalse( $dismisser->isDismissible() );
	}
}