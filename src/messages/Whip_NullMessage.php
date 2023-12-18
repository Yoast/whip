<?php

namespace Yoast\WHIPv2\Messages;

use Yoast\WHIPv2\Interfaces\Whip_Message;

/**
 * Class Whip_Message.
 */
class Whip_NullMessage implements Whip_Message {

	/**
	 * Retrieves the message body.
	 *
	 * @return string Message.
	 */
	public function body() {
		return '';
	}
}
