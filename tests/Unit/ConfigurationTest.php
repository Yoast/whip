<?php

namespace Yoast\WHIP\Tests\Unit;

use Yoast\WHIPv2\Configuration;

/**
 * Configuration unit tests.
 */
final class ConfigurationTest extends TestCase {

	/**
	 * Tests the creation of a Configuration with invalid input.
	 *
	 * @covers \Yoast\WHIPv2\Configuration::__construct
	 *
	 * @return void
	 */
	public function testItThrowsAnErrorIfAFaultyConfigurationIsPassed() {
		$this->expectExceptionHelper( '\Yoast\WHIPv2\Exceptions\InvalidType', 'Configuration should be of type array. Found string.' );

		new Configuration( 'Invalid configuration' );
	}

	/**
	 * Tests if Configuration correctly returns -1 when passed an unknown requirement.
	 *
	 * @covers \Yoast\WHIPv2\Configuration::configuredVersion
	 *
	 * @return void
	 */
	public function testItReturnsANegativeNumberIfRequirementCannotBeFound() {
		$configuration = new Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->willReturn( 'mysql' );

		$this->assertSame( -1, $configuration->configuredVersion( $requirement ) );
	}

	/**
	 * Tests if Configuration correctly returns the version number when passed a valid requirement.
	 *
	 * @covers \Yoast\WHIPv2\Configuration::configuredVersion
	 *
	 * @return void
	 */
	public function testItReturnsAnEntryIfRequirementIsFound() {
		$configuration = new Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->willReturn( 'php' );

		$this->assertSame( '5.6', $configuration->configuredVersion( $requirement ) );
	}

	/**
	 * Tests if hasRequirementConfigures correctly returns true/false when called with valid/invalid values.
	 *
	 * @covers \Yoast\WHIPv2\Configuration::hasRequirementConfigured
	 *
	 * @return void
	 */
	public function testIfRequirementIsConfigured() {
		$configuration = new Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->willReturn( 'php' );

		$falseRequirement = $this->getMockBuilder( '\Yoast\WHIPv2\Interfaces\Requirement' )
			->setMethods( array( 'component', 'version', 'operator' ) )
			->getMock();

		$falseRequirement
			->expects( $this->any() )
			->method( 'component' )
			->willReturn( 'mysql' );

		$this->assertTrue( $configuration->hasRequirementConfigured( $requirement ) );
		$this->assertFalse( $configuration->hasRequirementConfigured( $falseRequirement ) );
	}
}
