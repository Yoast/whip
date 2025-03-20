<?php

namespace Yoast\WHIP\Tests\Unit;

use Error;
use Exception;
use stdClass;
use Yoast\WHIPv2\RequirementsChecker;
use Yoast\WHIPv2\VersionRequirement;

/**
 * Requirements checker unit tests.
 */
final class RequirementsCheckerTest extends TestCase {

	/**
	 * Tests if RequirementsChecker is successfully created when given valid arguments.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::totalRequirements
	 *
	 * @return void
	 */
	public function testItReceivesAUsableRequirementObject() {
		$checker = new RequirementsChecker();
		$checker->addRequirement( new VersionRequirement( 'php', '5.2' ) );

		$this->assertTrue( $checker->hasRequirements() );
		$this->assertSame( 1, $checker->totalRequirements() );
	}

	/**
	 * Tests if RequirementsChecker throws an error when passed an invalid requirement.
	 *
	 * @covers   \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @requires PHP 7
	 *
	 * @return void
	 */
	public function testItThrowsAnTypeErrorWhenInvalidRequirementIsPassed() {
		if ( \version_compare( \phpversion(), '7.0', '<' ) ) {
			$this->markTestSkipped( 'Skipped due to incompatible PHP version: this test only runs on PHP 7+.' );
		}

		$exceptionCaught = false;

		$checker = new RequirementsChecker();

		try {
			$checker->addRequirement( new stdClass() );
		} catch ( Error $e ) {
			$exceptionCaught = true;
		}

		$this->assertTrue( $exceptionCaught );
	}

	/**
	 * Tests if RequirementsChecker throws an error when passed an invalid requirement.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 *
	 * @return void
	 */
	public function testItThrowsAnTypeErrorWhenInvalidRequirementIsPassedInPHP5() {
		if ( \version_compare( \phpversion(), '7.0', '>=' ) ) {
			$this->markTestSkipped( 'Skipped due to incompatible PHP version: this test only runs on PHP 5.x.' );
		}

		$exceptionCaught = false;

		$checker = new RequirementsChecker();

		try {
			$checker->addRequirement( new stdClass() );
		} catch ( Exception $e ) {
			$exceptionCaught = true;
		}

		$this->assertTrue( $exceptionCaught );
	}

	/**
	 * Tests if RequirementsChecker only saves unique components.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::totalRequirements
	 *
	 * @return void
	 */
	public function testItOnlyContainsUniqueComponents() {
		$checker = new RequirementsChecker();

		$checker->addRequirement( new VersionRequirement( 'php', '5.2' ) );
		$checker->addRequirement( new VersionRequirement( 'mysql', '5.6' ) );

		$this->assertTrue( $checker->hasRequirements() );
		$this->assertSame( 2, $checker->totalRequirements() );

		$checker->addRequirement( new VersionRequirement( 'php', '6' ) );

		$this->assertSame( 2, $checker->totalRequirements() );
	}

	/**
	 * Tests if RequirementsChecker::requirementExistsForComponent correctly
	 * returns true for existing components.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::requirementExistsForComponent
	 *
	 * @return void
	 */
	public function testIfRequirementExists() {
		$checker = new RequirementsChecker();

		$checker->addRequirement( new VersionRequirement( 'php', '5.2' ) );
		$checker->addRequirement( new VersionRequirement( 'mysql', '5.6' ) );

		$this->assertTrue( $checker->requirementExistsForComponent( 'php' ) );
		$this->assertFalse( $checker->requirementExistsForComponent( 'mongodb' ) );
	}

	/**
	 * Tests a situation in which the php requirement is not met.
	 *
	 * Verifies that a php upgrade message is created and successfully transferred to a variable.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::check
	 * @covers \Yoast\WHIPv2\RequirementsChecker::hasMessages
	 * @covers \Yoast\WHIPv2\RequirementsChecker::getMostRecentMessage
	 *
	 * @return void
	 */
	public function testCheckIfPHPRequirementIsNotFulfilled() {
		$checker = new RequirementsChecker( array( 'php' => '4' ) );

		$checker->addRequirement( new VersionRequirement( 'php', '5.6' ) );
		$checker->check();

		$this->assertTrue( $checker->hasMessages() );

		// Get the message. This will unset the global.
		$recentMessage = $checker->getMostRecentMessage();

		$this->assertNotEmpty( $recentMessage );

		if ( \method_exists( $this, 'assertIsString' ) ) {
			// PHPUnit 8+.
			$this->assertIsString( $recentMessage->body() );
		}
		else {
			$this->assertInternalType( 'string', $recentMessage->body() );
		}

		$this->assertFalse( $checker->hasMessages() );
		$this->assertInstanceOf( '\Yoast\WHIPv2\Messages\UpgradePhpMessage', $recentMessage );
	}

	/**
	 * Tests if there no message when the requirement is fulfilled.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::check
	 * @covers \Yoast\WHIPv2\RequirementsChecker::getMostRecentMessage
	 *
	 * @return void
	 */
	public function testCheckIfRequirementIsFulfilled() {
		$checker = new RequirementsChecker( array( 'php' => \phpversion() ) );

		$checker->addRequirement( new VersionRequirement( 'php', '5.2' ) );
		$checker->check();

		$this->assertEmpty( $checker->getMostRecentMessage()->body() );
	}

	/**
	 * Tests a situation in which the mysql requirement is not met.
	 *
	 * Verifies that an invalid version message is created and successfully transferred to a variable.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::check
	 * @covers \Yoast\WHIPv2\RequirementsChecker::getMostRecentMessage
	 * @covers \Yoast\WHIPv2\RequirementsChecker::hasMessages
	 *
	 * @return void
	 */
	public function testCheckIfRequirementIsNotFulfilled() {
		$checker = new RequirementsChecker( array( 'mysql' => '4' ) );

		$checker->addRequirement( new VersionRequirement( 'mysql', '5.6' ) );
		$checker->check();

		$this->assertTrue( $checker->hasMessages() );

		// Get the message. This will unset the global.
		$recentMessage = $checker->getMostRecentMessage();

		$this->assertNotEmpty( $recentMessage );
		$this->assertFalse( $checker->hasMessages() );
		$this->assertInstanceOf( '\Yoast\WHIPv2\Messages\InvalidVersionRequirementMessage', $recentMessage );
		$this->assertStringStartsWith( 'Invalid version detected', $recentMessage->body() );
	}

	/**
	 * Tests if a specific comparison with a non-default operator is correctly handled.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::check
	 * @covers \Yoast\WHIPv2\RequirementsChecker::hasMessages
	 *
	 * @return void
	 */
	public function testCheckIfRequirementIsFulfilledWithSpecificComparison() {
		$checker = new RequirementsChecker( array( 'php' => '4' ) );

		$checker->addRequirement( new VersionRequirement( 'php', '5.2', '<' ) );
		$checker->check();

		$this->assertFalse( $checker->hasMessages() );
	}

	/**
	 * Tests if a specific comparison with a non-default operator is correctly handled.
	 *
	 * @covers \Yoast\WHIPv2\RequirementsChecker::addRequirement
	 * @covers \Yoast\WHIPv2\RequirementsChecker::check
	 * @covers \Yoast\WHIPv2\RequirementsChecker::hasMessages
	 *
	 * @return void
	 */
	public function testCheckIfRequirementIsNotFulfilledWithSpecificComparison() {
		$checker = new RequirementsChecker( array( 'php' => '4' ) );

		$checker->addRequirement( new VersionRequirement( 'php', '5.2', '>=' ) );
		$checker->check();

		$this->assertTrue( $checker->hasMessages() );
	}
}
