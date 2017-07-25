<?php

if ( ! function_exists( 'whip_wp_check_versions' ) ) {
	/**
	 * Facade to quickly check if version requirements are met.
	 *
	 * @param array $requirements The requirements to check.
	 */
	function whip_wp_check_versions( $requirements ) {
		// Only show for admin users.
		if ( ! is_array( $requirements ) ) {
			return;
		}

		global $wp_version;


		$config  = include dirname( __FILE__ ) . '/../configs/default.php';
		$checker = new Whip_RequirementsChecker( $config );

		$dismisser = new Whip_MessageDismisser( $wp_version, new Whip_WPDismissOption() );

		$dismissListener = new Whip_WPMessageDismissListener( $dismisser );
		$dismissListener->listen();

		if ( ! $dismisser->isDismissed() ) {
			return;
		}

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
