<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Requirements checker unit tests.
 */
class RequirementsCheckerTest extends Whip_TestCase {

	/**
	 * Tests if Whip_RequirementsChecker is successfully created when given valid arguments.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::totalRequirements
	 */
	public function testItReceivesAUsableRequirementObject() {
		$checker = new Whip_RequirementsChecker();
		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2' ) );

		$this->assertTrue( $checker->hasRequirements() );
		$this->assertSame( 1, $checker->totalRequirements() );
	}

	/**
	 * Tests if Whip_RequirementsChecker throws an error when passed an invalid requirement.
	 *
	 * @covers   Whip_RequirementsChecker::addRequirement
	 * @requires PHP 7
	 */
	public function testItThrowsAnTypeErrorWhenInvalidRequirementIsPassed() {
		if ( version_compare( phpversion(), '7.0', '<' ) ) {
			$this->markTestSkipped( 'Skipped due to incompatible PHP version: this test only runs on PHP 7+.' );
		}

		$exceptionCaught = false;

		$checker = new Whip_RequirementsChecker();

		try {
			$checker->addRequirement( new stdClass() );
		} catch ( Error $e ) {
			$exceptionCaught = true;
		}

		$this->assertTrue( $exceptionCaught );
	}

	/**
	 * Tests if Whip_RequirementsChecker throws an error when passed an invalid requirement.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 */
	public function testItThrowsAnTypeErrorWhenInvalidRequirementIsPassedInPHP5() {
		if ( version_compare( phpversion(), '7.0', '>=' ) ) {
			$this->markTestSkipped( 'Skipped due to incompatible PHP version: this test only runs on PHP 5.x.' );
		}

		$exceptionCaught = false;

		$checker = new Whip_RequirementsChecker();

		try {
			$checker->addRequirement( new stdClass() );
		} catch ( Exception $e ) {
			$exceptionCaught = true;
		}

		$this->assertTrue( $exceptionCaught );
	}

	/**
	 * Tests if Whip_RequirementsChecker only saves unique components.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::totalRequirements
	 */
	public function testItOnlyContainsUniqueComponents() {
		$checker = new Whip_RequirementsChecker();

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2' ) );
		$checker->addRequirement( new Whip_VersionRequirement( 'mysql', '5.6' ) );

		$this->assertTrue( $checker->hasRequirements() );
		$this->assertSame( 2, $checker->totalRequirements() );

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '6' ) );

		$this->assertSame( 2, $checker->totalRequirements() );
	}

	/**
	 * Tests if Whip_RequirementsChecker::requirementExistsForComponent correctly
	 * returns true for existing components.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::requirementExistsForComponent
	 */
	public function testIfRequirementExists() {
		$checker = new Whip_RequirementsChecker();

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2' ) );
		$checker->addRequirement( new Whip_VersionRequirement( 'mysql', '5.6' ) );

		$this->assertTrue( $checker->requirementExistsForComponent( 'php' ) );
		$this->assertFalse( $checker->requirementExistsForComponent( 'mongodb' ) );
	}

	/**
	 * Tests a situation in which the php requirement is not met.
	 *
	 * Verifies that a php upgrade message is created and successfully transferred to a variable.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::check
	 * @covers Whip_RequirementsChecker::hasMessages
	 * @covers Whip_RequirementsChecker::getMostRecentMessage
	 */
	public function testCheckIfPHPRequirementIsNotFulfilled() {
		$checker = new Whip_RequirementsChecker( array( 'php' => 4 ) );

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.6' ) );
		$checker->check();

		$this->assertTrue( $checker->hasMessages() );

		// Get the message. This will unset the global.
		$recentMessage = $checker->getMostRecentMessage();

		$this->assertNotEmpty( $recentMessage );

		if ( method_exists( $this, 'assertIsString' ) ) {
			// PHPUnit 8+.
			$this->assertIsString( $recentMessage->body() );
		}
		else {
			$this->assertInternalType( 'string', $recentMessage->body() );
		}

		$this->assertFalse( $checker->hasMessages() );
		$this->assertInstanceOf( 'Whip_UpgradePhpMessage', $recentMessage );
	}

	/**
	 * Tests if there no message when the requirement is fulfilled.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::check
	 * @covers Whip_RequirementsChecker::getMostRecentMessage
	 */
	public function testCheckIfRequirementIsFulfilled() {
		$checker = new Whip_RequirementsChecker( array( 'php' => phpversion() ) );

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2' ) );
		$checker->check();

		$this->assertEmpty( $checker->getMostRecentMessage()->body() );
	}

	/**
	 * Tests a situation in which the mysql requirement is not met.
	 *
	 * Verifies that an invalid version message is created and successfully transferred to a variable.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::check
	 * @covers Whip_RequirementsChecker::getMostRecentMessage
	 * @covers Whip_RequirementsChecker::hasMessages
	 */
	public function testCheckIfRequirementIsNotFulfilled() {
		$checker = new Whip_RequirementsChecker( array( 'mysql' => 4 ) );

		$checker->addRequirement( new Whip_VersionRequirement( 'mysql', '5.6' ) );
		$checker->check();

		$this->assertTrue( $checker->hasMessages() );

		// Get the message. This will unset the global.
		$recentMessage = $checker->getMostRecentMessage();

		$this->assertNotEmpty( $recentMessage );
		$this->assertFalse( $checker->hasMessages() );
		$this->assertInstanceOf( 'Whip_InvalidVersionRequirementMessage', $recentMessage );
		$this->assertStringStartsWith( 'Invalid version detected', $recentMessage->body() );
	}

	/**
	 * Tests if a specific comparison with a non-default operator is correctly handled.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::check
	 * @covers Whip_RequirementsChecker::hasMessages
	 */
	public function testCheckIfRequirementIsFulfilledWithSpecificComparison() {
		$checker = new Whip_RequirementsChecker( array( 'php' => 4 ) );

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2', '<' ) );
		$checker->check();

		$this->assertFalse( $checker->hasMessages() );
	}

	/**
	 * Tests if a specific comparison with a non-default operator is correctly handled.
	 *
	 * @covers Whip_RequirementsChecker::addRequirement
	 * @covers Whip_RequirementsChecker::check
	 * @covers Whip_RequirementsChecker::hasMessages
	 */
	public function testCheckIfRequirementIsNotFulfilledWithSpecificComparison() {
		$checker = new Whip_RequirementsChecker( array( 'php' => 4 ) );

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2', '>=' ) );
		$checker->check();

		$this->assertTrue( $checker->hasMessages() );
	}
}
