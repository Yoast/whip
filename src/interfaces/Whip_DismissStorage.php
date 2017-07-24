<?php

/**
 * Interface Whip_DismissStorage
 */
interface Whip_DismissStorage {

	/**
	 * Saves the value.
	 *
	 * @param array $dismissed The value to save.
	 */
	public function save( array $dismissed );

	/**
	 * Returns the value.
	 *
	 * @return array
	 */
	public function get();

}