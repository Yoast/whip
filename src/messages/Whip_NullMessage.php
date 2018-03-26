<?php
/**
 * WHIP libary file.
 *
 * @package Yoast\WHIP
 */

/**
 * Class Whip_Message.
 */
class Whip_NullMessage implements Whip_Message {

	/**
	 * Retrieve the message body.
	 *
	 * @return string Message.
	 */
	public function body() {
		return '';
	}
}
