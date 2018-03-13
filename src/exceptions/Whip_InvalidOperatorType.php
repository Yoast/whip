<?php
/**
 * WHIP libary file.
 *
 * @package Yoast\WHIP
 */

/**
 * Class InvalidOperatorType.
 */
class Whip_InvalidOperatorType extends Exception {

	private $validOperators = array( '=', '==', '===', '<', '>', '<=', '>=' );

	/**
	 * InvalidOperatorType constructor.
	 *
	 * @param string $value Invalid operator.
	 */
	public function __construct( $value ) {
		parent::__construct(
			sprintf( 'Invalid operator of %s used. Please use one of the following operators: %s',
				$value,
				implode( ', ', $this->validOperators )
			) );
	}
}
