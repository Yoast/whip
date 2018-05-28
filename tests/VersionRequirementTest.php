<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Version requirements unit tests.
 */
class VersionRequirementTest extends PHPUnit_Framework_TestCase {

	public function testNameAndVersionAreNotEmpty() {
		$requirement = new Whip_VersionRequirement( 'php', '5.2' );

		$this->assertNotEmpty( $requirement->component() );
		$this->assertNotEmpty( $requirement->version() );
	}

	/**
	 * @expectedException Whip_EmptyProperty
	 * @expectedExceptionMessage Component cannot be empty.
	 */
	public function testComponentCannotBeEmpty() {
		new Whip_VersionRequirement( '', '5.2' );
	}

	/**
	 * @expectedException Whip_EmptyProperty
	 * @expectedExceptionMessage Version cannot be empty.
	 */
	public function testVersionCannotBeEmpty() {
		new Whip_VersionRequirement( 'php', '' );
	}

	/**
	 * @expectedException Whip_InvalidType
	 * @expectedExceptionMessage Component should be of type string. Found integer.
	 */
	public function testComponentMustBeString() {
		new Whip_VersionRequirement( 123, '5.2' );
	}

	/**
	 * @expectedException Whip_InvalidType
	 * @expectedExceptionMessage Version should be of type string. Found integer.
	 */
	public function testVersionMustBeString() {
		new Whip_VersionRequirement( 'php', 123 );
	}

	/**
	 * @expectedException Whip_EmptyProperty
	 * @expectedExceptionMessage Operator cannot be empty.
	 */
	public function testOperatorCannotBeEmpty() {
		new Whip_VersionRequirement( 'php', '5.6', '' );
	}

	/**
	 * @expectedException Whip_InvalidType
	 * @expectedExceptionMessage Operator should be of type string. Found integer.
	 */
	public function testOperatorMustBeString() {
		new Whip_VersionRequirement( 'php', '5.2', 6 );
	}

	/**
	 * @expectedException Whip_InvalidOperatorType
	 * @expectedExceptionMessage Invalid operator of -> used. Please use one of the following operators: =, ==, ===, <, >, <=, >=
	 */
	public function testOperatorMustBeValid() {
		new Whip_VersionRequirement( 'php', '5.2', '->' );
	}

	public function testGettingComponentProperties() {
		$requirement = new Whip_VersionRequirement( 'php', '5.6' );

		$this->assertEquals( 'php', $requirement->component() );
		$this->assertEquals( '5.6', $requirement->version() );
		$this->assertEquals( '=', $requirement->operator() );
	}

	/**
	 * @dataProvider dataFromCompareString
	 */
	public function testFromCompareString( $expectation, $component, $compareString ) {
		$requirement = Whip_VersionRequirement::fromCompareString( $component, $compareString );

		$this->assertEquals( $expectation[0], $requirement->component() );
		$this->assertEquals( $expectation[1], $requirement->version() );
		$this->assertEquals( $expectation[2], $requirement->operator() );
	}

	public function dataFromCompareString() {
		return array(
			array( array( 'php', '5.5', '>' ), 'php', '>5.5' ),
			array( array( 'php', '5.5', '>=' ), 'php', '>=5.5' ),
			array( array( 'php', '5.5', '<' ), 'php', '<5.5' ),
			array( array( 'php', '5.5', '<=' ), 'php', '<=5.5' ),
			array( array( 'php', '7.3', '>' ), 'php', '>7.3' ),
			array( array( 'php', '7.3', '>=' ), 'php', '>=7.3' ),
			array( array( 'php', '7.3', '<' ), 'php', '<7.3' ),
			array( array( 'php', '7.3', '<=' ), 'php', '<=7.3' ),
		);
	}

	/**
	 * @expectedException Whip_InvalidVersionComparisonString
	 * @expectedExceptionMessage Invalid version comparison string. Example of a valid version comparison string: >=5.3. Passed version comparison string: > 2.3
	 */
	public function testFromCompareStringException() {
		Whip_VersionRequirement::fromCompareString( 'php', '> 2.3' );
	}
}

