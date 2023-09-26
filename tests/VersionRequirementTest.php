<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Version requirements unit tests.
 */
class VersionRequirementTest extends Whip_TestCase {

	/**
	 * Creates a new Whip_VersionRequirement with component php and version 5.2 and
	 * tests if this is correctly created.
	 *
	 * @covers Whip_VersionRequirement::component
	 * @covers Whip_VersionRequirement::version
	 *
	 * @return void
	 */
	public function testNameAndVersionAreNotEmpty() {
		$requirement = new Whip_VersionRequirement( 'php', '5.2' );

		$this->assertNotEmpty( $requirement->component() );
		$this->assertNotEmpty( $requirement->version() );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with an empty component.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testComponentCannotBeEmpty() {
		$this->expectExceptionHelper( 'Whip_EmptyProperty', 'Component cannot be empty.' );

		new Whip_VersionRequirement( '', '5.2' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with an empty version.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testVersionCannotBeEmpty() {
		$this->expectExceptionHelper( 'Whip_EmptyProperty', 'Version cannot be empty.' );

		new Whip_VersionRequirement( 'php', '' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with a false type for a component.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testComponentMustBeString() {
		$this->expectExceptionHelper( 'Whip_InvalidType', 'Component should be of type string. Found integer.' );

		new Whip_VersionRequirement( 123, '5.2' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with a false type for a version.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testVersionMustBeString() {
		$this->expectExceptionHelper( 'Whip_InvalidType', 'Version should be of type string. Found integer.' );

		new Whip_VersionRequirement( 'php', 123 );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with an empty operator.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testOperatorCannotBeEmpty() {
		$this->expectExceptionHelper( 'Whip_EmptyProperty', 'Operator cannot be empty.' );

		new Whip_VersionRequirement( 'php', '5.6', '' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with a false type for an operator.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testOperatorMustBeString() {
		$this->expectExceptionHelper( 'Whip_InvalidType', 'Operator should be of type string. Found integer.' );

		new Whip_VersionRequirement( 'php', '5.2', 6 );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a Whip_VersionRequirement
	 * is created with an invalid operator.
	 *
	 * @covers Whip_VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testOperatorMustBeValid() {
		$this->expectExceptionHelper(
			'Whip_InvalidOperatorType',
			'Invalid operator of -> used. Please use one of the following operators: =, ==, ===, <, >, <=, >='
		);

		new Whip_VersionRequirement( 'php', '5.2', '->' );
	}

	/**
	 * Creates a new Whip_VersionRequirement and tests if this is correctly created with its given arguments.
	 *
	 * @covers Whip_VersionRequirement::component
	 * @covers Whip_VersionRequirement::version
	 * @covers Whip_VersionRequirement::operator
	 *
	 * @return void
	 */
	public function testGettingComponentProperties() {
		$requirement = new Whip_VersionRequirement( 'php', '5.6' );

		$this->assertSame( 'php', $requirement->component() );
		$this->assertSame( '5.6', $requirement->version() );
		$this->assertSame( '=', $requirement->operator() );
	}

	/**
	 * Tests whether fromCompareString() correctly returns the expected variables from a passed string.
	 *
	 * @dataProvider dataFromCompareString
	 *
	 * @covers Whip_VersionRequirement::component
	 * @covers Whip_VersionRequirement::version
	 * @covers Whip_VersionRequirement::operator
	 *
	 * @param string $expectation   The expected output string.
	 * @param string $component     The component for this version requirement.
	 * @param string $compareString The comparison string for this version requirement.
	 *
	 * @return void
	 */
	public function testFromCompareString( $expectation, $component, $compareString ) {
		$requirement = Whip_VersionRequirement::fromCompareString( $component, $compareString );

		$this->assertSame( $expectation[0], $requirement->component() );
		$this->assertSame( $expectation[1], $requirement->version() );
		$this->assertSame( $expectation[2], $requirement->operator() );
	}

	/**
	 * Provides data to test fromCompareString with.
	 *
	 * @return array<string, array<string|array<string>>>
	 */
	public function dataFromCompareString() {
		return array(
			'php > 5.5'  => array( array( 'php', '5.5', '>' ), 'php', '>5.5' ),
			'php >= 5.5' => array( array( 'php', '5.5', '>=' ), 'php', '>=5.5' ),
			'php < 5.5'  => array( array( 'php', '5.5', '<' ), 'php', '<5.5' ),
			'php <= 5.5' => array( array( 'php', '5.5', '<=' ), 'php', '<=5.5' ),
			'php > 7.3'  => array( array( 'php', '7.3', '>' ), 'php', '>7.3' ),
			'php >= 7.3' => array( array( 'php', '7.3', '>=' ), 'php', '>=7.3' ),
			'php < 7.3'  => array( array( 'php', '7.3', '<' ), 'php', '<7.3' ),
			'php <= 7.3' => array( array( 'php', '7.3', '<=' ), 'php', '<=7.3' ),
		);
	}

	/**
	 * Tests whether fromCompareString() correctly throws an exception when provided
	 * with an invalid comparison string.
	 *
	 * @covers Whip_VersionRequirement::fromCompareString
	 *
	 * @return void
	 */
	public function testFromCompareStringException() {
		$this->expectExceptionHelper(
			'Whip_InvalidVersionComparisonString',
			'Invalid version comparison string. Example of a valid version comparison string: >=5.3. Passed version comparison string: > 2.3'
		);

		Whip_VersionRequirement::fromCompareString( 'php', '> 2.3' );
	}
}

