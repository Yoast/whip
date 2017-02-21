<?php

class StaticVersionDetector implements Whip_VersionDetector {
	public function __construct( $version ) {
		$this->version = $version;
	}

	public function detect() {
		return $this->version;
	}

	public function getMessage() {
		return '';
	}
}

class MessageCollector implements Whip_MessagePresenter {
	protected $messages = array();

	public function show( $message ) {
		$this->messages[] = $message;
	}

	public function getMessages() {
		return $this->messages;
	}
}

/**
 * @property Whip_VersionMessage   versionMessageControl
 * @property StaticVersionDetector versionDetector
 */
class VersionMessageControlTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->versionDetector = new StaticVersionDetector( '1.0' );

		$this->versionMessageControl = new Whip_VersionMessage(
			$this->versionDetector,
			array()
		);
	}

	public function testConstructor() {

		$versionMessageControl = new Whip_VersionMessage(
			$this->versionDetector,
			array()
		);

		$this->assertEquals( $this->versionDetector, $versionMessageControl->getVersionDetector() );
		$this->assertEquals( array(), $versionMessageControl->getMessagePresenters() );
	}

	/**
	 *
	 */
	public function testRequireVersion() {
		$versionMessageControl = new Whip_VersionMessage(
			$this->versionDetector,
			array()
		);

		$versionMessageControl->requireVersion( "1.0" );
	}

	/**
	 * @dataProvider dataIsStatisfied
	 */
	public function testIsStatisfied( $expected, $installedVersion, $requiredVersion ) {
		$this->versionMessageControl->setVersionDetector( new StaticVersionDetector( $installedVersion ) );

		$this->assertEquals( $expected, $this->versionMessageControl->isStatisfied( $requiredVersion ) );
	}

	public function testShowMessages() {
		$messenger1 = new MessageCollector();
		$messenger2 = new MessageCollector();

		$this->versionMessageControl->setMessagePresenters( array( $messenger1 ) );

		$this->versionMessageControl->showMessage( "Message1" );
		$this->versionMessageControl->setMessagePresenters( array( $messenger1, $messenger2 ) );

		$this->versionMessageControl->showMessage( "Message2" );

		$this->assertEquals( array( "Message1", "Message2" ), $messenger1->getMessages() );
		$this->assertEquals( array( "Message2" ), $messenger2->getMessages() );
	}

	public function dataIsStatisfied() {
		return array(
			array( true, '1.0', '1.0' ),
			array( false, '1.0', '1.1' ),
			array( true, '1.0', '0.9' ),
			array( false, '1.0-alpha1.0', '1.0' ),
			array( false, '1.0-beta2.0', '1.0' ),
			array( true, '1.0-alpha1.0', '0.99' ),
			array( true, '1.0-beta2.0', '0.99' ),
		);
	}
}
