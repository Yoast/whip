<?php

/**
 * A class to dismiss messages.
 */
class Whip_MessageDismisser {

	/** @var Whip_DismissStorage */
	protected $storage;

	/** @var string */
	protected $currentTime;

	/**
	 * Whip_MessageDismisser constructor.
	 *
	 * @param int                 $currentTime The current time.
	 * @param Whip_DismissStorage $storage     The storage object handling storage of the time.
	 */
	public function __construct( $currentTime, Whip_DismissStorage $storage ) {
		$this->currentTime = $currentTime;
		$this->storage     = $storage;
	}

	/**
	 * Saves the version number to the storage to indicate the message as being dismissed.
	 */
	public function dismiss() {
		$this->storage->set( $this->currentTime );
	}

	/**
	 * Checks if the stored time is lower than the current time.
	 *
	 * @return bool True when stored value + threshold is bigger than current time.
	 */
	public function isDismissed() {
		return ( ( $this->storage->get() + $this->getThreshold() ) > $this->currentTime );
	}

	/**
	 * Returns the threshold.
	 *
	 * @return integer The total amount of seconds.
	 */
	protected function getThreshold() {
		$numberOfWeeks = 4;

		return ( WEEK_IN_SECONDS * $numberOfWeeks );
	}

}
