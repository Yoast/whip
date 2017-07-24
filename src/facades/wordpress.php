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

		$config  = include dirname( __FILE__ ) . '/../configs/default.php';
		$checker = new Whip_RequirementsChecker( $config );

		$dismisser = new Whip_MessageDismisser( time() + MONTH_IN_SECONDS, new Whip_WPDismissOption() );

		$dismissListener = new Whip_WPMessageDismissListener( $dismisser );
		$dismissListener->listen();

		foreach ( $requirements as $component => $versionComparison ) {
			if ( $dismisser->isDismissed( $versionComparison, $component ) ) {
				continue;
			}

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
