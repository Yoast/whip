<?php


class VersionMessageControl {

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
	 * Returns the configured version detector
	 *
	 * @return VersionDetector
	 */
	public function getVersionDetector() {
		return $this->versionDetector;
	}

	/**
	 * Returns the configured message presenters
	 *
	 * @return MessagePresenter
	 */
	public function getMessagePresenters() {
		return $this->messagePresenters;
	}
}

