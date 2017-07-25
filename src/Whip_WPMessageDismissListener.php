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
	 *
	 * @param Whip_MessageDismisser $dismisser The object for dismissing a message.
	 */
	public function __construct( Whip_MessageDismisser $dismisser ) {
		$this->dismisser = $dismisser;
	}

	/**
	 * Listens to a GET request to fetch the required attributes.
	 *
	 * @return void
	 */
	public function listen() {
		$action = filter_input( INPUT_GET, 'action' );
		$nonce  = filter_input( INPUT_GET, 'nonce' );

		if ( $action === 'whip_dismiss' && wp_verify_nonce( $nonce, 'whip_dismiss' ) ) {
			$this->dismisser->dismiss();
		}
	}

	/**
	 * Creates an url for dismissing the notice.
	 *
	 * @return string The url for dismissing the message.
	 */
	public static function get_dismissurl() {
		return sprintf(
			admin_url( 'index.php?action=whip_dismiss&nonce=%1$s' ),
			wp_create_nonce( 'whip_dismiss' )
		);
	}

}