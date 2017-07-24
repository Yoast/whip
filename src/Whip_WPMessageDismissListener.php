<?php

/**
 * Listener for dismissing a message.
 */
class Whip_WPMessageDismissListener implements Whip_Listener {

	/**
	 * @var Whip_MessageDismisser
	 */
	protected $dismisser;

	/**
	 * Sets the dismisser attribute.
	 */
	public function __construct() {
		$this->dismisser = new Whip_MessageDismisser( time() + MONTH_IN_SECONDS, new Whip_WPDismissOption() );
	}

	/**
	 * Listens to a GET request to fetch the required attributes.
	 */
	public function listen() {
		$action = filter_input( INPUT_GET, 'action' );
		$nonce  = filter_input(INPUT_GET, 'nonce' );

		if ( $action === 'whip_dismiss' && wp_verify_nonce( $nonce, 'whip_dismiss' ) ) {
			$component = (string) filter_input( INPUT_GET, 'component' );
			$version_compare = (string) filter_input( INPUT_GET, 'version' );

			$this->dismisser->dismiss( urldecode( $component ), urldecode( $version_compare ) );
		}
	}

	/**
	 * Creates an url for dismissing the notice.
	 *
	 * @param string $component The component.
	 * @param string $version   The version comparison string.
	 *
	 * @return string .
	 */
	public static function get_dismissurl( $component, $version ) {
		return sprintf(
			admin_url( 'index.php?action=whip_dismiss&nonce=%1$s&component=%2$s&version=%3$s' ),
			wp_create_nonce( 'whip_dismiss' ),
			urlencode( $component ),
			urlencode( $version )
		);
	}

}