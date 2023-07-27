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

/**
 * Mock for sanitize_text_field.
 *
 * @param string $text The text to be sanitize.
 *
 * @return string The text that was sanitized.
 */
function sanitize_text_field( $text ) {
	return $text;
}

/**
 * Mock for wp_unslash.
 *
 * @param string $string The string to be wp_unslash.
 *
 * @return string The string that was unslashed.
 */
function wp_unslash( $string ) {
	return $string;
}
