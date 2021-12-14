<?php
/**
 * WHIP libary file.
 *
 * @package Yoast\WHIP
 */

/**
 * Main controller class to require a certain version of software.
 */
class Whip_RequirementsChecker {

	/**
	 * Requirements the environment should comply with.
	 *
	 * @var Whip_Requirement[]
	 */
	private $requirements;

	/**
	 * The current configuration.
	 *
	 * @var Whip_Configuration
	 */
	private $configuration;

	/**
	 * Keeps track of messages that should be shown.
	 *
	 * @var Whip_MessagesManager
	 */
	private $messageManager;

	/**
	 * Provides messages for display given a requirement.
	 *
	 * @var Whip_MessageProvider
	 */
	private $messageProvider;

	/**
	 * Whip_RequirementsChecker constructor.
	 *
	 * @param Whip_Configuration   $configuration   The configuration to check.
	 * @param Whip_MessageProvider $messageProvider A class that provides messages for unmet requirements.
	 */
	public function __construct( Whip_Configuration $configuration, Whip_MessageProvider $messageProvider ) {
		$this->requirements    = array();
		$this->configuration   = $configuration;
		$this->messageProvider = $messageProvider;
		$this->messageManager  = new Whip_MessagesManager();
	}

	/**
	 * Adds a requirement to the list of requirements if it doesn't already exist.
	 *
	 * @param Whip_Requirement $requirement The requirement to add.
	 */
	public function addRequirement( Whip_Requirement $requirement ) {
		// Only allow unique entries to ensure we're not checking specific combinations multiple times.
		if ( $this->requirementExistsForComponent( $requirement->component() ) ) {
			return;
		}

		$this->requirements[] = $requirement;
	}

	/**
	 * Determines whether there are requirements available.
	 *
	 * @return bool Whether there are requirements.
	 */
	public function hasRequirements() {
		return $this->totalRequirements() > 0;
	}

	/**
	 * Gets the total amount of requirements.
	 *
	 * @return int The total amount of requirements.
	 */
	public function totalRequirements() {
		return count( $this->requirements );
	}

	/**
	 * Determines whether a requirement exists for a particular component.
	 *
	 * @param string $component The component to check for.
	 *
	 * @return bool Whether the component has a requirement defined.
	 */
	public function requirementExistsForComponent( $component ) {
		foreach ( $this->requirements as $requirement ) {
			if ( $requirement->component() === $component ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determines whether a requirement has been fulfilled.
	 *
	 * @param Whip_Requirement $requirement The requirement to check.
	 *
	 * @return bool Whether the requirement is fulfilled.
	 */
	private function requirementIsFulfilled( Whip_Requirement $requirement ) {
		$availableVersion = $this->configuration->configuredVersion( $requirement );
		$requiredVersion  = $requirement->version();

		if ( in_array( $requirement->operator(), array( '=', '==', '===' ), true ) ) {
			return version_compare( $availableVersion, $requiredVersion, '>=' );
		}

		return version_compare( $availableVersion, $requiredVersion, $requirement->operator() );
	}

	/**
	 * Checks if all requirements are fulfilled and adds a message to the message manager if necessary.
	 */
	public function check() {
		foreach ( $this->requirements as $requirement ) {
			// Match against config.
			$requirementFulfilled = $this->requirementIsFulfilled( $requirement );

			if ( $requirementFulfilled ) {
				continue;
			}

			$this->addMissingRequirementMessage( $requirement );
		}
	}

	/**
	 * Adds a message to the message manager for requirements that cannot be fulfilled.
	 *
	 * @param Whip_Requirement $requirement The requirement that cannot be fulfilled.
	 */
	private function addMissingRequirementMessage( Whip_Requirement $requirement ) {
		$message = $this->messageProvider->getMessage( $requirement, $this->configuration );
		$this->messageManager->addMessage( $message );
	}

	/**
	 * Determines whether there are messages available.
	 *
	 * @return bool Whether there are messages to display.
	 */
	public function hasMessages() {
		return $this->messageManager->hasMessages();
	}

	/**
	 * Gets the most recent message from the message manager.
	 *
	 * @return Whip_Message The latest message.
	 */
	public function getMostRecentMessage() {
		return $this->messageManager->getLatestMessage();
	}
}
