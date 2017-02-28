<?php

class RequirementsCheckerTest extends PHPUnit_Framework_TestCase {
	public function testItReceivesAUsableRequirementObject() {
		$checker = new Whip_RequirementsChecker();
		$checker->addRequirement( new Whip_VersionRequirement( 'php', '>=5.2' ) );

		$this->assertTrue( $checker->hasRequirements() );
		$this->assertEquals( 1, $checker->totalRequirements() );
	}

	/**
	 * @expectedException TypeError
	 */
	public function testItThrowsAnTypeErrorWhenInvalidRequirementIsPassed() {
		$checker = new Whip_RequirementsChecker();
		$checker->addRequirement( new \stdClass() );
	}

	public function testItOnlyContainsUniqueComponents() {
		$checker = new Whip_RequirementsChecker();

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '>=5.2' ) );
		$checker->addRequirement( new Whip_VersionRequirement( 'mysql', '5.6' ) );

		$this->assertTrue( $checker->hasRequirements() );
		$this->assertEquals( 2, $checker->totalRequirements() );

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '6' ) );

		$this->assertEquals( 2, $checker->totalRequirements() );
	}

	public function testIfRequirementExists() {
		$checker = new Whip_RequirementsChecker();

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '>=5.2' ) );
		$checker->addRequirement( new Whip_VersionRequirement( 'mysql', '5.6' ) );

		$this->assertTrue( $checker->requirementExistsForComponent( 'php' ) );
		$this->assertFalse( $checker->requirementExistsForComponent( 'mongodb' ) );
	}

	public function testCheckIfRequirementIsFulfilled() {
		$checker = new Whip_RequirementsChecker( array( 'php' => phpversion() )	);

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.2' ) );
		$checker->check();

		$this->assertEmpty( $checker->getMostRecentMessage() );
	}

	public function testCheckIfPHPRequirementIsNotFulfilled() {
		$checker = new Whip_RequirementsChecker( array( 'php' => 4 )	);

		$checker->addRequirement( new Whip_VersionRequirement( 'php', '5.6' ) );
		$checker->check();

		$recentMessage = $checker->getMostRecentMessage();

		$this->assertTrue( $checker->hasMessages() );
		$this->assertNotEmpty( $recentMessage  );

		$this->assertTrue( get_class( $recentMessage ) === Whip_UpgradePhpMessage::class );
	}

	public function testCheckIfRequirementIsNotFulfilled() {
		$checker = new Whip_RequirementsChecker( array( 'mysql' => 4 )	);

		$checker->addRequirement( new Whip_VersionRequirement( 'mysql', '5.6' ) );
		$checker->check();

		$recentMessage = $checker->getMostRecentMessage();

		$this->assertTrue( $checker->hasMessages() );
		$this->assertNotEmpty( $recentMessage  );
		$this->assertTrue( get_class( $recentMessage ) === Whip_InvalidVersionRequirementMessage::class );
	}
}
