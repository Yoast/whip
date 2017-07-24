<?php

/**
 * Represents the WordPress option for saving the dismissed messages.
 */
class Whip_WPDismissOption implements Whip_DismissStorage {

	/**
	 * Saves the value to the options.
	 *
	 * @param string $dismissedVersion The value to save.
	 */
	public function set( $dismissedVersion ) {
		update_option( 'whip_dismissed', $dismissedVersion );
	}

	/**
	 * Returns the value of the whip_dismissed option.
	 *
	 * @return string
	 */
	public function get() {
		$dismissedOption = get_option( 'whip_dismissed' );
		if ( ! $dismissedOption ) {
			return '';
		}

		return $dismissedOption;
	}

}