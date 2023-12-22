<?php

namespace Yoast\WHIP\Tests\Unit\Doubles;

use Yoast\WHIPv2\Interfaces\DismissStorage;

/**
 * Test helper.
 */
final class DismissStorageMock implements DismissStorage {

	/**
	 * Holds the dismissed state.
	 *
	 * @var int
	 */
	protected $dismissed = 0;

	/**
	 * Saves the value.
	 *
	 * @param int $dismissedValue The value to save.
	 *
	 * @return bool
	 */
	public function set( $dismissedValue ) {
		$this->dismissed = $dismissedValue;

		return true;
	}

	/**
	 * Returns the value.
	 *
	 * @return int
	 */
	public function get() {
		return $this->dismissed;
	}
}
