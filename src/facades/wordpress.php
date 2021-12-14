<?php
/**
 * WHIP libary file.
 *
 * @package Yoast\WHIP
 */

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

		$config = include __DIR__ . '/../configs/default.php';

		$messageProvider    = new Whip_MessageProvider();
		$requirementObjects = array();
		foreach ( $requirements as $component => $requirement ) {
			$requirementObject    = Whip_VersionRequirement::fromCompareString( $component, $requirement['version'] );
			$requirementObjects[] = $requirementObject;
			if ( isset( $requirement['message'] ) ) {
				$messageProvider->addMessage( $requirement['message'], $requirementObject );
			}
		}

		$checker = new Whip_RequirementsChecker( new Whip_Configuration( $config ), $messageProvider );

		foreach ( $requirementObjects as $requirement ) {
			$checker->addRequirement( $requirement );
		}

		$checker->check();

		if ( ! $checker->hasMessages() ) {
			return;
		}

		$dismissThreshold = ( WEEK_IN_SECONDS * 4 );
		$dismissMessage   = __( 'Remind me again in 4 weeks.', 'default' );

		$dismisser = new Whip_MessageDismisser( time(), $dismissThreshold, new Whip_WPDismissOption() );

		$presenter = new Whip_WPMessagePresenter( $checker->getMostRecentMessage(), $dismisser, $dismissMessage );

		// Prevent duplicate notices across multiple implementing plugins.
		if ( ! has_action( 'whip_register_hooks' ) ) {
			add_action( 'whip_register_hooks', array( $presenter, 'registerHooks' ) );
		}

		/**
		 * Fires during hooks registration for the message presenter.
		 *
		 * @param \Whip_WPMessagePresenter $presenter Message presenter instance.
		 */
		do_action( 'whip_register_hooks', $presenter );
	}
}
