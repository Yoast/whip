<?php

/**
 * Represents the WordPress option for saving the dismissed messages.
 */
class Whip_WPDismissOption implements Whip_DissmisStorage {

	/**
	 * Saves the value to the options.
	 *
	 * @param array $dismissed The value to save.
	 */
	public function save( array $dismissed ) {
		update_option( 'whip_dismissed', $dismissed );
	}

	/**
	 * Returns the value of the whip_dismissed option.
	 *
	 * @return array
	 */
	public function get() {
		$dismissedOption = maybe_unserialize( get_option( 'whip_dismissed' ) );
		if ( ! $dismissedOption ) {
			return array() ;
		}

		return $dismissedOption;
	}

}