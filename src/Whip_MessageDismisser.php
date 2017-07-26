<?php


/**
 * A class to dismiss messages.
 */
class Whip_MessageDismisser {

	/** @var Whip_DismissStorage */
	protected $storage;

	/** @var string */
	protected $currentVersion;

	/**
	 * Whip_MessageDismisser constructor.
	 *
	 * @param string              $currentVersion The current version of the installation.
	 * @param Whip_DismissStorage $storage        The storage object handling storage of versioning.
	 */
	public function __construct( $currentVersion, Whip_DismissStorage $storage ) {
		$this->currentVersion = $this->toMajorVersion( $currentVersion );
		$this->storage        = $storage;
	}

	/**
	 * Saves the version number to the storage to indicate the message as being dismissed.
	 */
	public function dismiss() {
		$this->storage->set( $this->currentVersion );
	}

	/**
	 * Checks if the saved version is lower than the current version.
	 *
	 * @return bool True when saved version is lower than the current version.
	 */
	public function isDismissed() {
		return version_compare( $this->storage->get(), $this->currentVersion, '<' );
	}

	/**
	 * Converts the version number to a major version number.
	 *
	 * @param string $versionToConvert The version to convert.
	 *
	 * @return string The major version number.
	 */
	protected function toMajorVersion( $versionToConvert ) {
		$parts = explode( '.', $versionToConvert, 3 );

		return implode( '.', array_slice( $parts, 0, 2 ) );
	}
}
