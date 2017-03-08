<?php

/**
 * Represents a host
 */
class Whip_Host {
	const HOST_NAME_KEY = 'WHIP_NAME_OF_HOST';

	/**
	 * Retrieves the name of the host if set.
	 *
	 * @return string The name of the host.
	 */
	public static function name() {
		$name = (string) getenv( self::HOST_NAME_KEY );

		return self::filter_name( $name );
	}

	/**
	 * Filters the name if we are in a WordPress context.
	 *
	 * @param string $name The current name of the host.
	 * @returns string The filtered name of the host.
	 */
	private static function filter_name( $name ) {
		if ( function_exists( 'apply_filters' ) ) {
			return (string) apply_filters( strtolower( self::HOST_NAME_KEY ), $name );
		}

		return $name;
	}

	/**
	 * Retrieves the message from the host if set.
	 *
	 * @param string $messageKey The key to use as the environment variable.
	 *
	 * @return string The message as set by the host.
	 */
	public static function message( $messageKey ) {
		$message = (string) getenv( $messageKey );

		return self::filter_message( $messageKey, $message );
	}

	/**
	 * Filters the message if we are in a WordPress context.
	 *
	 * @param string $filterKey The key used for the environment variable.
	 * @param string $message The current message from the host.
	 *
	 * @return string
	 */
	private static function filter_message( $messageKey, $message ) {
		if ( function_exists( 'apply_filters' ) ) {
			return (string) apply_filters( strtolower( $messageKey ), $message );
		}

		return $message;
	}
}
