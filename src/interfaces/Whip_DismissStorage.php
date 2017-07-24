<?php

/**
 * Interface Whip_DismissStorage
 */
interface Whip_DismissStorage {

	/**
	 * Saves the value.
	 *
	 * @param string $dismissedVersion The value to save.
	 */
	public function set( $dismissedVersion );

	/**
	 * Returns the value.
	 *
	 * @return array
	 */
	public function get();

}