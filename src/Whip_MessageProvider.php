<?php
/**
 * WHIP libary file.
 *
 * @package Yoast\WHIP
 */

/**
 * Provides messages to display when a requirement isn't met.
 */
class Whip_MessageProvider {

	/**
	 * The list of messages that are registered.
	 *
	 * @var Whip_Message[]
	 */
	private $registeredMessages;

	/**
	 * Whip_MessageProvider constructor.
	 */
	public function __construct() {
		$this->registeredMessages = array();
	}

	/**
	 * Adds a message to the list of messages and overwrites it if it already exists.
	 *
	 * @param Whip_Message     $message     The message to register.
	 * @param Whip_Requirement $requirement The requirement that the message is for.
	 */
	public function addMessage( Whip_Message $message, Whip_Requirement $requirement ) {
		$this->registeredMessages[ $requirement->component() ] = $message;
	}

	/**
	 * Gets the message for a given requirement.
	 *
	 * @param Whip_Requirement   $requirement   The requirement to get the message for.
	 * @param Whip_Configuration $configuration The current configuration.
	 *
	 * @return Whip_Message
	 */
	public function getMessage( Whip_Requirement $requirement, Whip_Configuration $configuration ) {
		$component = $requirement->component();
		if ( $this->messageExistsForComponent( $component ) ) {
			return $this->registeredMessages[ $component ];
		}

		// Fallback to a generic message.
		return new Whip_InvalidVersionRequirementMessage( $requirement, $configuration->configuredVersion( $requirement ) );
	}

	/**
	 * Determines whether a message exists for a particular component.
	 *
	 * @param string $component The component to check for.
	 *
	 * @return bool Whether the component has a message defined.
	 */
	private function messageExistsForComponent( $component ) {
		return isset( $this->registeredMessages[ $component ] );
	}
}
