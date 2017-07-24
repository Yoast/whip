<?php


/**
 * A class to dismiss messages
 */
final class Whip_MessageDismisser {

	/** @var Whip_DismissStorage */
	protected $storage;

	/** @var int */
	private $currentVersion;

	/**
	 * Whip_MessageDismisser constructor.
	 *
	 * @param int                 $currentVersion
	 * @param Whip_DismissStorage $storage
	 */
	public function __construct( $currentVersion, Whip_DismissStorage $storage ) {
		$this->currentVersion   = $currentVersion;
		$this->storage          = $storage;
	}

	/**
	 * Dismisses the message for the given component/version.
	 */
	public function dismiss() {
		$this->storage->set( $this->currentVersion );
	}

	/**
	 * Checks if the dismissed version is lower then the installation version.
	 *
	 * @return bool
	 */
	public function isDismissible() {
		return version_compare( $this->currentVersion, $this->storage->get(), '>' );
	}
}
