<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Test helper.
 */
class Whip_DismissStorageMock implements Whip_DismissStorage {

	/**
	 * Creates $dismissed and sets it to 0.
	 *
	 * @var int
	 */
	protected $dismissed = 0;

	/**
	 * Saves the value.
	 *
	 * @param int $dismissedValue The value to save.
	 *
	 * @return boolean
	 */
	public function set( $dismissedValue ) {
		$this->dismissed = $dismissedValue;

		return true;
	}

	/**
	 * Returns the value.
	 *
	 * @return int
	 */
	public function get() {
		return $this->dismissed;
	}
}
