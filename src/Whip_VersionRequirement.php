<?php

/**
 * Class Whip_Requirement
 */
class Whip_VersionRequirement implements Whip_Requirement {
	/**
	 * @var string
	 */
	private $component;

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var string
	 */
	private $operator;

	/**
	 * Whip_Requirement constructor.
	 *
	 * @param string $component
	 * @param string $version
	 * @param string $operator
	 */
	public function __construct( $component, $version, $operator = '=' ) {
		$this->validateParameters( $component, $version, $operator );

		$this->component = $component;
		$this->version   = $version;
		$this->operator  = $operator;
	}

	/**
	 * Gets the component name defined for the requirement.
	 *
	 * @return string The component name.
	 */
	public function component() {
		return $this->component;
	}

	/**
	 * Gets the components version defined for the requirement.
	 *
	 * @return string
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Returns the operator to use when comparing version numbers.
	 *
	 * @return string The comparison operator.
	 */
	public function operator() {
		return $this->operator;
	}

	/**
	 * Creates a new version requirement from a comparison string
	 *
	 * @throws InvalidVersionComparisonString When an invalid version comparison string is passed.
	 * @param string $component        The component for this version requirement.
	 * @param string $comparisonString The comparison string for this version requirement.
	 * @returns Whip_VersionRequirement The parsed version requirement.
	 */
	public static function fromCompareString( $component, $comparisonString ) {

		$matcher = '(' .
		               '(>=?)' . // Matches >= and >.
		               '|' .
		               '(<=?)' . // Matches <= and <.
		           ')' .
		           '([^>=<\s]+)'; // Matches anything except >, <, =, and whitespace.

		if ( ! preg_match( '#' . $matcher . '#', $comparisonString, $match ) ) {
			throw new InvalidVersionComparisonString( $comparisonString );
		}

		$version = $match[4];
		$operator = $match[1];

		return new Whip_VersionRequirement( $component, $version, $operator );
	}

	/**
	 * Validates the parameters passed to the requirement.
	 *
	 * @param string $component The compoenent name.
	 * @param string $version   The component version.
	 * @param string $operator  The operator to use when comparing version.
	 *
	 * @throws EmptyProperty
	 * @throws InvalidOperatorType
	 * @throws InvalidType
	 */
	private function validateParameters( $component, $version, $operator ) {
		if ( empty( $component ) ) {
			throw new EmptyProperty( "Component" );
		}

		if ( !is_string( $component ) ) {
			throw new InvalidType( "Component", "string", $component );
		}

		if ( empty( $version ) ) {
			throw new EmptyProperty( "Version" );
		}

		if ( !is_string( $version ) ) {
			throw new InvalidType( "Version", "string", $version );
		}

		if ( empty( $operator ) ) {
			throw new EmptyProperty( "Operator" );
		}

		if ( !is_string( $operator ) ) {
			throw new InvalidType( 'Operator', 'string', $operator );
		}

		if ( ! in_array( $operator, array( '=', '==', '===', '<', '>', '<=', '>=' ) ) ) {
			throw new InvalidOperatorType( $operator );
		}
	}
}
