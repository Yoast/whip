<?php


/**
 * A class to dismiss messages
 */
final class Whip_WPMessageDismisser {

	/**
	 * Returns the value of the whip_dismissed option.
	 *
	 * @return mixed
	 */
	public function getDismissed() {
		return maybe_unserialize( get_option( 'whip_dismissed' ) );
	}

	/**
	 * Listens to a GET request to fetch the required attributes.
	 */
	public function listen() {

		$action = filter_input( INPUT_GET, 'action' );
		$nonce  = filter_input(INPUT_GET, 'nonce' );

		if ( $action === 'whip_dismiss' && wp_verify_nonce( $nonce, 'whip_dismiss' ) ) {
			$component = (string) filter_input( INPUT_GET, 'component' );
			$version_compare = (string) filter_input( INPUT_GET, 'action' );

			$this->dismiss( $component, $version_compare );
		}
	}

	/**
	 * Dismisses the message for the given component/version.
	 *
	 * @param string $component       The component to dismiss.
	 * @param string $version_compare The version comparison string.
	 */
	private function dismiss( $component, $version_compare ) {
		$dismissed = $this->getDismissed();

		if ( ! isset( $dismissed[ $component ] ) ) {
			$dismissed[ $component ] = array();
		}

		$dismissed[ $component ][ $version_compare ] = time();

		update_option( 'whip_dismissed', $dismissed );
	}

}
