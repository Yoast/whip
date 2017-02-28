<?php

/**
 * Facade to quickly check if version requirements are met.
 *
 * @param array $requirements The requirements to check.
 */
function CheckVersions( $requirements ) {
	// Only show for admin users.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
 	}

 	if ( ! is_array( $requirements ) ) {
		return;
    }

	$checker = new Whip_RequirementsChecker( $requirements );
	$checker->check();

	if ( ! $checker->hasMessages() ) {
		return;
	}

	$presenter = new Whip_WPMessagePresenter( $checker->getMostRecentMessage() );
	$presenter->register_hooks();
}
