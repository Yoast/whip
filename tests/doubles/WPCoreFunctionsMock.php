<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

if ( ! defined( 'WEEK_IN_SECONDS' ) ) {
	define( 'WEEK_IN_SECONDS', ( 60 * 60 * 24 * 7 ) );
}

/**
 * Returns the message.
 *
 * @param string $message The message to return.
 *
 * @return string The message that is returned.
 */
function __( $message ) {
	return $message;
}

/**
 * Returns the url.
 *
 * @param string $url The url to return.
 *
 * @return string The url that is returned.
 */
function esc_url( $url ) {
	return $url;
}
