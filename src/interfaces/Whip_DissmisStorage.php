<?php

/**
 * Interface Whip_DissmissStorage
 */
interface Whip_DissmisStorage {

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