<?php

/**
 * Class Whip_MessageFormatter
 */
class Whip_MessageFormatter {

	/**
	 * Wraps a piece of text in HTML strong tags
	 *
	 * @param string $toWrap The text to wrap.
	 * @return string The wrapped text.
	 */
	public static function strong( $toWrap ) {
		return '<strong>' . $toWrap . '</strong>';
	}

	/**
	 * Wraps a piece of text in HTML p tags
	 *
	 * @param string $toWrap The text to wrap.
	 * @return string The wrapped text.
	 */
	public static function paragraph( $toWrap ) {
		return '<p>' . $toWrap . '</p>';
	}

	/**
	 * Wraps a piece of text in HTML p and strong tags
	 *
	 * @param string $toWrap The text to wrap.
	 * @return string The wrapped text.
	 */
	public static function strongParagraph( $toWrap ) {
		return '<p><strong>' . $toWrap . '</strong></p>';
	}
}
