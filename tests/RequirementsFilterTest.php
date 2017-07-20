<?php


class RequirementsFilterTest extends PHPUnit_Framework_TestCase {

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
			array( $currentTime + ( 3600 * 24 * 100 ), array( 'php' => array( '>=5.4' => $currentTime ) ), array( 'php' => '>=5.4' ), array() ),
			array( $currentTime, array( 'php' => array( '>=5.4' => $currentTime + 100 ) ), array( 'php' => '>=5.4' ) ),
			array( $currentTime, array(), array( 'php' => '>=5.4' ) ),
			array( $currentTime, array( 'php' => array() ), array( 'php' => '>=5.4' ) ),
			array( $currentTime, array( 'php' => array( '>=5.2' => $currentTime ) ), array( 'php' => '>=5.4' ) ),
		);

	}

}