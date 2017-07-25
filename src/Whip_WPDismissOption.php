<?php

/**
 * Represents the WordPress option for saving the dismissed messages.
 */
class Whip_WPDismissOption implements Whip_DismissStorage {

	/** @var string */
	protected $option_name = 'whip_dismissed_for_wp_version';

	/**
	 * Saves the value to the options.
	 *
	 * @param string $dismissedVersion The value to save.
	 *
	 * @return bool True when successfull.
	 */
	public function set( $dismissedVersion ) {
		return update_option( $this->option_name, $dismissedVersion );
	}

	/**
	 * Returns the value of the whip_dismissed option.
	 *
	 * @return string Returns the value of the option or an empty string when not set.
	 */
	public function get() {
		$dismissedOption = get_option( $this->option_name );
		if ( ! $dismissedOption ) {
			return '';
		}

		return $dismissedOption;
	}

}
