<?php

/**
 * Send a message to the user to update the versions of their software.
 */
class Whip_VersionMessage {

	/**
	 * @var VersionDetector
	 */
	protected $versionDetector;

	/**
	 * @var MessagePresenter[]
	 */
	protected $messagePresenters;

	public function __construct( VersionDetector $versionDetector, array $messagePresenters ) {
		$this->versionDetector = $versionDetector;
		$this->messagePresenters = $messagePresenters;
	}
	/**
	 * Requires a certain version for the given version detector and show a message using the message presenter.
	 *
	 * @param string $version
	 */
	public function requireVersion( $version ) {
		if ( ! $this->isStatisfied( $version ) ) {
			$this->showMessage( $this->versionDetector->getMessage() );
		}
	}

	/**
	 * Returns if the given version is statisfied by the installed version
	 *
	 * @param string $required_version The required version.
	 * @returns bool
	 */
	public function isStatisfied( $required_version ) {
		$current_version = $this->versionDetector->detect();

		return version_compare( $current_version, $required_version, ">=" );
	}

	/**
	 * Shows the version message to the user with all messengers.
	 *
	 * @param string $message The message to show to the user.
	 */
	public function showMessage( $message ) {
		foreach ( $this->messagePresenters as $messagePresenter ) {
			$messagePresenter->show( $message );
		}
	}


	/**
	 * Returns the configured version detector
	 *
	 * @return VersionDetector
	 */
	public function getVersionDetector() {
		return $this->versionDetector;
	}

	/**
	 * @param VersionDetector $versionDetector
	 */
	public function setVersionDetector( $versionDetector ) {
		$this->versionDetector = $versionDetector;
	}

	/**
	 * Returns the configured message presenters
	 *
	 * @return MessagePresenter[]
	 */
	public function getMessagePresenters() {
		return $this->messagePresenters;
	}

	/**
	 * @param MessagePresenter[] $messagePresenters
	 */
	public function setMessagePresenters( $messagePresenters ) {
		$this->messagePresenters = $messagePresenters;
	}

	/**
	 * Requires certain versions in a WordPress environment
	 *
	 * @param array  $config The configuration for the messages.
	 * @param string $textdomain The textdomain to use for translating the messages.
	 * @return self[] Returns the instance we create to require the versions.
	 */
	public static function wp_require_versions( $config, $textdomain ) {
		$whips = array();
		$wpMessagePresenter = new WPMessagePresenter();
		$wpMessagePresenter->register_hooks();

		foreach ( $config as $type => $version ) {
			switch ( $type ) {
				default:
				case 'php':
					$detector = new PHPVersionDetector( $textdomain );
					break;
			}

			$whip = new self( $detector, array( $wpMessagePresenter ) );
			$whip->requireVersion( $version );

			$whips[] = $whip;
		}

		return $whips;
	}
}

