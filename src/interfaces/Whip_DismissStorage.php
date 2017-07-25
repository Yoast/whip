<?php

/**
 * Interface Whip_DismissStorage
 */
interface Whip_DismissStorage {

	/**
	 * Saves the value.
	 *
	 * @param string $dismissedVersion The value to save.
	 *
	 * @return bool True when successful.
	 */
	public function set( $dismissedVersion );

	/**
	 * Returns the value.
	 *
	 * @return string
	 */
	public function get();

}
