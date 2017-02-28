<?php

/**
 * Class Whip_MessagesManager
 */
class Whip_MessagesManager {

	public function __construct()
	{
		if ( ! array_key_exists( 'whip_messages', $GLOBALS ) ) {
			$GLOBALS['whip_messages'] = array();
		}
	}

	public function addMessage( Whip_Message $message ) {
		$whipVersion = require dirname( __FILE__ ) . '/version.php';

		$GLOBALS['whip_messages'][$whipVersion] = $message;
	}

	public function hasMessages() {
		return isset( $GLOBALS['whip_messages'] ) && count( $GLOBALS['whip_messages'] ) > 0;
	}

	public function listMessages() {
		return $GLOBALS[ 'whip_messages' ];
	}

	public function deleteMessages() {
		unset( $GLOBALS[ 'whip_messages' ] );
	}

	public function getLatestMessage() {
		if ( ! $this->hasMessages() ) {
			return '';
		}

		$messages = $this->listMessages();
		$this->deleteMessages();

		uksort( $messages, 'version_compare' );

		return array_pop( $messages );
	}
}
