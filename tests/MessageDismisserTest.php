<?php

class Whip_DismissStorageMock implements Whip_DissmisStorage {

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

		$this->assertTrue( $dismisser->isDismissed( '>=5.5', 'php' ) );

		$dismisser->dismiss( 'php', '>=5.4' );
		$this->assertTrue( $dismisser->isDismissed( '>=5.4', 'php' ) );


		$this->assertTrue( $dismisser->isDismissed( '>=5.2', 'php' ) );
	}

	public function testIsDismissedExpired() {
		$expirationDate = time() - ( 60 * 60 * 24 * 302 );
		$storage = new Whip_DismissStorageMock();
		$dismisser = new Whip_MessageDismisser( $expirationDate, $storage );

		$dismisser->dismiss( 'php', '>=5.4' );

		$this->assertFalse( $dismisser->isDismissed( '>=5.4', 'php' ) );
	}

	/**
	 * @dataProvider filterProvider
	 *
	 * @param       $expirationDate
	 * @param array $requirementsToFilter
	 * @param array $requirements
	 * @param null  $expected
	 */
	public function testFilter( $expirationDate, array $requirementsToFilter, array $requirements, $expected = null ) {
		if( $expected === null ) {
			$expected = $requirements;
		}
		$filter = new Whip_RequirementsFilter( $expirationDate, $requirementsToFilter );
		$requirements = $filter->filter( $requirements );

		$this->assertEquals( $expected, $requirements );
	}

	public function filterProvider() {

		$currentTime = time();

		return array(
			array( $currentTime, array( 'php' => array( '>=5.4' => $currentTime - 3600 * 24 * 101 ) ), array( 'php' => '>=5.4' ), array() ),
			array( $currentTime, array( 'php' => array( '>=5.4' => $currentTime + 100 ) ), array( 'php' => '>=5.4' ) ),
			array( $currentTime, array(), array( 'php' => '>=5.4' ) ),
			array( $currentTime, array( 'php' => array() ), array( 'php' => '>=5.4' ) ),
			array( $currentTime, array( 'php' => array( '>=5.2' => $currentTime ) ), array( 'php' => '>=5.4' ) ),
		);

	}

}