<?php

class VersionRequirementTest extends PHPUnit_Framework_TestCase {
	public function testNameAndVersionAreNotEmpty() {
		$requirement = new Whip_VersionRequirement( 'php', '5.2' );

		$this->assertNotEmpty($requirement->component());
		$this->assertNotEmpty($requirement->version());
	}

	/**
	 * @expectedException EmptyProperty
	 */
	public function testComponentCannotBeEmpty() {
		new Whip_VersionRequirement( '', '5.2' );
	}

	/**
	 * @expectedException EmptyProperty
	 */
	public function testVersionCannotBeEmpty() {
		new Whip_VersionRequirement( 'php', '' );
	}

	/**
	 * @expectedException InvalidType
	 */
	public function testComponentMustBeString() {
		new Whip_VersionRequirement( 123, '5.2' );
	}

	/**
	 * @expectedException InvalidType
	 */
	public function testVersionMustBeString() {
		new Whip_VersionRequirement( 'php', 123 );
	}

	/**
	 * @expectedException EmptyProperty
	 */
	public function testOperatorCannotBeEmpty() {
		new Whip_VersionRequirement( 'php', '5.6', '' );
	}

	/**
	 * @expectedException InvalidType
	 */
	public function testOperatorMustBeString() {
		new Whip_VersionRequirement( 'php', '5.2', 6 );
	}

	/**
	 * @expectedException InvalidOperatorType
	 */
	public function testOperatorMustBeValid() {
		new Whip_VersionRequirement( 'php', '5.2', '->' );
	}

	public function testGettingComponentProperties() {
		$requirement = new Whip_VersionRequirement( 'php', '5.6' );

		$this->assertEquals( 'php', $requirement->component() );
		$this->assertEquals( '5.6', $requirement->version() );
		$this->assertEquals( '=',   $requirement->operator() );
	}
}

