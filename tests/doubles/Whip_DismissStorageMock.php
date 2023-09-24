<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

/**
 * Test helper.
 */
final class Whip_DismissStorageMock implements Whip_DismissStorage {

	/**
	 * Holds the dismissed state.
	 *
	 * @var int
	 */
	protected $dismissed = 0;

	/**
	 * Saves the value.
	 *
	 * @param int $dismissedValue The value to save.
	 *
	 * @return bool
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
