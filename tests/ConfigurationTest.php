<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Configuration unit tests.
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests the creation of a Whip_Configuration with invalid input.
	 *
	 * @expectedException        Whip_InvalidType
	 * @expectedExceptionMessage Configuration should be of type array. Found string.
	 */
	public function testItThrowsAnErrorIfAFaultyConfigurationIsPassed() {
		$configuration = new Whip_Configuration( 'Invalid configuration' );
	}

	/**
	 * Tests if Whip_Configuration correctly returns -1 when passed an unknown requirement.
	 *
	 * @covers Whip_Configuration::configuredVersion
	 */
	public function testItReturnsANegativeNumberIfRequirementCannotBeFound() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( 'Whip_Requirement' )
			->setMethods( array( 'component' ) )
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
	 * @covers Whip_Configuration::configuredVersion
	 */
	public function testItReturnsAnEntryIfRequirementIsFound() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( 'Whip_Requirement' )
			->setMethods( array( 'component' ) )
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
	 * @covers Whip_Configuration::hasRequirementConfigured
	 */
	public function testIfRequirementIsConfigured() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement   = $this->getMockBuilder( 'Whip_Requirement' )
			->setMethods( array( 'component' ) )
			->getMock();

		$requirement
			->expects( $this->any() )
			->method( 'component' )
			->will( $this->returnValue( 'php' ) );

		$falseRequirement = $this->getMockBuilder( 'Whip_Requirement' )
			->setMethods( array( 'component' ) )
			->getMock();

		$falseRequirement
			->expects( $this->any() )
			->method( 'component' )
			->will( $this->returnValue( 'mysql' ) );

		$this->assertTrue( $configuration->hasRequirementConfigured( $requirement ) );
		$this->assertFalse( $configuration->hasRequirementConfigured( $falseRequirement ) );
	}
}
