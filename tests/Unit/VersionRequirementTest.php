<?php

namespace Yoast\WHIP\Tests\Unit;

use Yoast\WHIPv2\VersionRequirement;

/**
 * Version requirements unit tests.
 */
final class VersionRequirementTest extends TestCase {

	/**
	 * Creates a new VersionRequirement with component php and version 5.2 and
	 * tests if this is correctly created.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::component
	 * @covers \Yoast\WHIPv2\VersionRequirement::version
	 *
	 * @return void
	 */
	public function testNameAndVersionAreNotEmpty() {
		$requirement = new VersionRequirement( 'php', '5.2' );

		$this->assertNotEmpty( $requirement->component() );
		$this->assertNotEmpty( $requirement->version() );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with an empty component.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testComponentCannotBeEmpty() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\EmptyProperty', 'Component cannot be empty.' );

		new VersionRequirement( '', '5.2' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with an empty version.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testVersionCannotBeEmpty() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\EmptyProperty', 'Version cannot be empty.' );

		new VersionRequirement( 'php', '' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with a false type for a component.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testComponentMustBeString() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\InvalidType', 'Component should be of type string. Found integer.' );

		new VersionRequirement( 123, '5.2' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with a false type for a version.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testVersionMustBeString() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\InvalidType', 'Version should be of type string. Found integer.' );

		new VersionRequirement( 'php', 123 );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with an empty operator.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testOperatorCannotBeEmpty() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\EmptyProperty', 'Operator cannot be empty.' );

		new VersionRequirement( 'php', '5.6', '' );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with a false type for an operator.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testOperatorMustBeString() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\InvalidType', 'Operator should be of type string. Found integer.' );

		new VersionRequirement( 'php', '5.2', 6 );
	}

	/**
	 * Tests if an Exception message is correctly thrown when a VersionRequirement
	 * is created with an invalid operator.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::validateParameters
	 *
	 * @return void
	 */
	public function testOperatorMustBeValid() {
		$this->expectExceptionHelper(
			'\Yoast\WHIPv2\Exceptions\InvalidOperatorType',
			'Invalid operator of -> used. Please use one of the following operators: =, ==, ===, <, >, <=, >='
		);

		new VersionRequirement( 'php', '5.2', '->' );
	}

	/**
	 * Creates a new VersionRequirement and tests if this is correctly created with its given arguments.
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::component
	 * @covers \Yoast\WHIPv2\VersionRequirement::version
	 * @covers \Yoast\WHIPv2\VersionRequirement::operator
	 *
	 * @return void
	 */
	public function testGettingComponentProperties() {
		$requirement = new VersionRequirement( 'php', '5.6' );

		$this->assertSame( 'php', $requirement->component() );
		$this->assertSame( '5.6', $requirement->version() );
		$this->assertSame( '=', $requirement->operator() );
	}

	/**
	 * Tests whether fromCompareString() correctly returns the expected variables from a passed string.
	 *
	 * @dataProvider dataFromCompareString
	 *
	 * @covers \Yoast\WHIPv2\VersionRequirement::component
	 * @covers \Yoast\WHIPv2\VersionRequirement::version
	 * @covers \Yoast\WHIPv2\VersionRequirement::operator
	 *
	 * @param string $expectation   The expected output string.
	 * @param string $component     The component for this version requirement.
	 * @param string $compareString The comparison string for this version requirement.
	 *
	 * @return void
	 */
	public function testFromCompareString( $expectation, $component, $compareString ) {
		$requirement = VersionRequirement::fromCompareString( $component, $compareString );

		$this->assertSame( $expectation[0], $requirement->component() );
		$this->assertSame( $expectation[1], $requirement->version() );
		$this->assertSame( $expectation[2], $requirement->operator() );
	}

	/**
	 * Provides data to test fromCompareString with.
	 *
	 * @return array<string, array<string|array<string>>>
	 */
	public static function dataFromCompareString() {
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
	 * @covers \Yoast\WHIPv2\VersionRequirement::fromCompareString
	 *
	 * @return void
	 */
	public function testFromCompareStringException() {
		$this->expectExceptionHelper(
			'\Yoast\WHIPv2\Exceptions\InvalidVersionComparisonString',
			'Invalid version comparison string. Example of a valid version comparison string: >=5.3. Passed version comparison string: > 2.3'
		);

		VersionRequirement::fromCompareString( 'php', '> 2.3' );
	}
}
