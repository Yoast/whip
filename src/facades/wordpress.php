<?php

if ( ! function_exists( 'CheckVersions' ) ) {
	/**
	 * Facade to quickly check if version requirements are met.
	 *
	 * @param array $requirements The requirements to check.
	 */
	function CheckVersions( $requirements ) {
		// Only show for admin users.
		if ( ! current_user_can( 'manage_options' ) || ! is_array( $requirements ) ) {
			return;
		}

		$config  = include dirname( __FILE__ ) . '/../configs/default.php';
		$checker = new Whip_RequirementsChecker( $config );

		foreach ( $requirements as $component => $versionComparison ) {
			$checker->addRequirement( Whip_VersionRequirement::fromCompareString( $component, $versionComparison ) );
		}

		$checker->check();

		if ( ! $checker->hasMessages() ) {
			return;
		}

		$presenter = new Whip_WPMessagePresenter( $checker->getMostRecentMessage() );
		$presenter->register_hooks();
	}
}
