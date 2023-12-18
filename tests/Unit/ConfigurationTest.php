<?php

namespace Yoast\WHIP\Tests\Unit;

use Yoast\WHIPv2\Whip_Configuration;

/**
 * Configuration unit tests.
 */
final class ConfigurationTest extends TestCase {

	/**
	 * Tests the creation of a Whip_Configuration with invalid input.
	 *
	 * @covers \Yoast\WHIPv2\Whip_Configuration::__construct
	 *
	 * @return void
	 */
	public function testItThrowsAnErrorIfAFaultyConfigurationIsPassed() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\Whip_InvalidType', 'Configuration should be of type array. Found string.' );

		new Whip_Configuration( 'Invalid configuration' );
	}

	/**
	 * Tests if Whip_Configuration correctly returns -1 when passed an unknown requirement.
	 *
	 * @covers \Yoast\WHIPv2\Whip_Configuration::configuredVersion
	 *
	 * @return void
	 */
	public function testItReturnsANegativeNumberIfRequirementCannotBeFound() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Whip_Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->will( $this->returnValue( 'mysql' ) );

		$this->assertSame( -1, $configuration->configuredVersion( $requirement ) );
	}

	/**
	 * Tests if Whip_Configuration correctly returns the version number when passed a valid requirement.
	 *
	 * @covers \Yoast\WHIPv2\Whip_Configuration::configuredVersion
	 *
	 * @return void
	 */
	public function testItReturnsAnEntryIfRequirementIsFound() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Whip_Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->will( $this->returnValue( 'php' ) );

		$this->assertSame( '5.6', $configuration->configuredVersion( $requirement ) );
	}

	/**
	 * Tests if hasRequirementConfigures correctly returns true/false when called with valid/invalid values.
	 *
	 * @covers \Yoast\WHIPv2\Whip_Configuration::hasRequirementConfigured
	 *
	 * @return void
	 */
	public function testIfRequirementIsConfigured() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Whip_Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->will( $this->returnValue( 'php' ) );

		$falseRequirement = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Whip_Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$falseRequirement
			->expects( $this->any() )
			->method( 'component' )
			->will( $this->returnValue( 'mysql' ) );

		$this->assertTrue( $configuration->hasRequirementConfigured( $requirement ) );
		$this->assertFalse( $configuration->hasRequirementConfigured( $falseRequirement ) );
	}
}
