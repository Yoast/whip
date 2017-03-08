<?php

/**
 * Class Whip_Host
 */
class Whip_Host {
	const HOST_NAME_KEY = 'WHIP_NAME_OF_HOST';

	/**
	 * Retrieves the name of the host if set.
	 *
	 * @return string The name of the host.
	 */
	public static function name() {
		return (string) getenv( self::HOST_NAME_KEY );
	}

	/**
	 * Retrieves the message from the host if set.
	 *
	 * @param string $messageKey The key to use as the environment variable.
	 *
	 * @return string The message as set by the host.
	 */
	public static function message( $messageKey ) {
		return (string) getenv( $messageKey );
	}
}
