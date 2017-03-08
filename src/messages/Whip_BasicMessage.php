<?php

/**
 * Class Whip_Message
 */
class Whip_BasicMessage implements Whip_Message {
	/**
	 * @var string
	 */
	private $body;

	/**
	 * Whip_Message constructor.
	 *
	 * @param string $body
	 *
	 * @throws EmptyProperty
	 * @throws InvalidType
	 */
	public function __construct($body) {
		$this->validateParameters( $body );

		$this->body = $body;
	}

	/**
	 * @return string
	 */
	public function body() {
		return $this->body;
	}

	private function validateParameters( $body ) {
		if ( empty( $body ) ) {
			throw new EmptyProperty( "Message body" );
		}

		if ( ! is_string( $body ) ) {
			throw new InvalidType( "Message body", "string", $body );
		}
	}
}
