<?php

class Whip_DismissStorageMock implements Whip_DismissStorage {

	protected $dismissed = array();

	/**
	 * Saves the value.
	 *
	 * @param array $dismissed The value to save.
	 */
	public function save( array $dismissed ) {
		$this->dismissed = $dismissed;
	}

	/**
	 * Returns the value.
	 *
	 * @return array
	 */
	public function get() {
		return $this->dismissed;
	}
}


class MessageDismisserTest extends PHPUnit_Framework_TestCase {

	public function testDismiss() {
		$expirationDate = time() + 3600;
		$storage = new Whip_DismissStorageMock();
		$dismisser = new Whip_MessageDismisser( $expirationDate, $storage );

		$dismisser->dismiss( 'php', '>=5.4' );

		$this->assertEquals( array( 'php' => array( '>=5.4' => $expirationDate ) ), $storage->get() );
	}

	public function testIsDismissed() {
		$expirationDate = time() + 3600;
		$storage = new Whip_DismissStorageMock();
		$dismisser = new Whip_MessageDismisser( $expirationDate, $storage );

		$this->assertFalse( $dismisser->isDismissed( '>=5.5', 'php' ) );

		$dismisser->dismiss( 'php', '>=5.4' );
		$this->assertTrue( $dismisser->isDismissed( '>=5.4', 'php' ) );

		$this->assertFalse( $dismisser->isDismissed( '>=5.2', 'php' ) );
	}

	public function testIsDismissedExpired() {
		$expirationDate = time() - ( 60 * 60 * 24 * 32 );
		$storage = new Whip_DismissStorageMock();
		$dismisser = new Whip_MessageDismisser( $expirationDate, $storage );

		$dismisser->dismiss( 'php', '>=5.4' );

		$this->assertFalse( $dismisser->isDismissed( '>=5.4', 'php' ) );
	}
}