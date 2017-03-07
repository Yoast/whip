<?php

class ConfigurationTest extends PHPUnit_Framework_TestCase {
	/**
	 * @expectedException InvalidType
	 */
	public function testItThrowsAnErrorIfAFaultyConfigurationIsPassed() {
		$configuration = new Whip_Configuration( 'Invalid configuration' );
	}

	public function testItReturnsANegativeNumberIfRequirementCannotBeFound() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement = $this->getMockBuilder( 'Whip_Requirement' )
							->setMethods( array( 'component' ) )
							->getMock();

		$requirement->method('component')
			->willReturn('mysql');

		$this->assertEquals( -1, $configuration->configuredVersion( $requirement ) );
	}

	public function testItReturnsAnEntryIfRequirementIsFound() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement = $this->getMockBuilder( 'Whip_Requirement' )
		                    ->setMethods( array( 'component' ) )
		                    ->getMock();

		$requirement->method('component')->willReturn('php');

		$this->assertEquals( '5.6', $configuration->configuredVersion( $requirement ) );
	}

	public function testIfRequirementIsConfigurated() {
		$configuration = new Whip_Configuration( array( 'php' => '5.6' ) );
		$requirement = $this->getMockBuilder( 'Whip_Requirement' )
		                    ->setMethods( array( 'component' ) )
		                    ->getMock();

		$requirement->method('component')->willReturn('php');

		$falseRequirement = $this->getMockBuilder( 'Whip_Requirement' )
		                         ->setMethods( array( 'component' ) )
		                         ->getMock();

		$falseRequirement->method('component')->willReturn('mysql');

		$this->assertTrue( $configuration->hasRequirementConfigurated( $requirement ) );
		$this->assertFalse( $configuration->hasRequirementConfigurated( $falseRequirement ) );
	}
}
