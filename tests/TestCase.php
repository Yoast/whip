<?php
/**
 * WHIP libary test file.
 *
 * @package Yoast\WHIP
 */

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

/**
 * Base test case class from which all tests should extend.
 */
abstract class TestCase extends PHPUnit_TestCase {

	/**
	 * Helper method to test exceptions.
	 *
	 * PHPUnit 4 contained the `setExpectedException()` method which could test various
	 * aspects of exceptions.
	 * PHPUnit 5 introduced a new set of methods to test exceptions, splitting the previous
	 * functionality up.
	 * Aside from that PHPUnit has the `@expectException...` annotations, but support for
	 * those is deprecated in PHPUnit 8 and removed in PHPUnit 9.
	 *
	 * All in all, a helper method is needed to allow for testing exceptions in a PHPUnit
	 * cross-version compatible manner.
	 *
	 * @param string          $exception The class name of the exception to expect.
	 * @param string          $message   Optional. The exception message to expect.
	 * @param int|string|null $code      Optional. The exception code to expect.
	 *
	 * @return void
	 */
	public function expectExceptionHelper( $exception, $message = '', $code = null ) {
		if ( method_exists( $this, 'expectException' ) ) {
			// PHPUnit 5+.
			$this->expectException( $exception );

			if ( $message !== '' ) {
				$this->expectExceptionMessage( $message );
			}

			if ( isset( $code ) ) {
				$this->expectExceptionCode( $code );
			}
		}
		else {
			// PHPUnit 4.
			$this->setExpectedException( $exception, $message, $code );
		}
	}
}
