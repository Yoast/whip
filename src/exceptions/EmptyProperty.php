<?php

/**
 * Class EmptyProperty
 */
class EmptyProperty extends Exception {
	public function __construct( $property ) {
	    parent::__construct( sprintf( '%s cannot be empty.', (string) $property ) );
	}
}
