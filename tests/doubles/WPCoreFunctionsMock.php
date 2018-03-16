<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

if ( ! defined( 'WEEK_IN_SECONDS' ) ) {
	define( 'WEEK_IN_SECONDS', ( 60 * 60 * 24 * 7 ) );
}

function __( $message ) {
	return $message;
}

function esc_url( $url ) {
	return $url;
}
