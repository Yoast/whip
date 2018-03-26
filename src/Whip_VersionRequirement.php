<?php
/**
 * WHIP libary file.
 *
 * @package Yoast\WHIP
 */

/**
 * A value object containing a version requirement for a component version.
 */
class Whip_VersionRequirement implements Whip_Requirement {

	/**
	 * The component name.
	 *
	 * @var string
	 */
	private $component;

	/**
	 * The component version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * The operator to use when comparing version.
	 *
	 * @var string
	 */
	private $operator;

	/**
	 * Whip_Requirement constructor.
	 *
	 * @param string $component The component name.
	 * @param string $version   The component version.
	 * @param string $operator  The operator to use when comparing version.
	 */
	public function __construct( $component, $version, $operator = '=' ) {
		$this->validateParameters( $component, $version, $operator );

		$this->component = $component;
		$this->version   = $version;
		$this->operator  = $operator;
	}

	/**
	 * Get the component name defined for the requirement.
	 *
	 * @return string The component name.
	 */
	public function component() {
		return $this->component;
	}

	/**
	 * Get the components version defined for the requirement.
	 *
	 * @return string
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Get the operator to use when comparing version numbers.
	 *
	 * @return string The comparison operator.
	 */
	public function operator() {
		return $this->operator;
	}

	/**
	 * Create a new version requirement from a comparison string.
	 *
	 * @throws Whip_InvalidVersionComparisonString When an invalid version comparison string is passed.
	 *
	 * @param string $component        The component for this version requirement.
	 * @param string $comparisonString The comparison string for this version requirement.
	 *
	 * @returns Whip_VersionRequirement The parsed version requirement.
	 */
	public static function fromCompareString( $component, $comparisonString ) {

		$matcher = '`
			(
				>=?     # Matches >= and >.
				|
				<=?     # Matches <= and <.
			)
			([^>=<\s]+) # Matches anything except >, <, =, and whitespace.
		`x';

		if ( ! preg_match( $matcher, $comparisonString, $match ) ) {
			throw new Whip_InvalidVersionComparisonString( $comparisonString );
		}

		$version  = $match[2];
		$operator = $match[1];

		return new Whip_VersionRequirement( $component, $version, $operator );
	}

	/**
	 * Validate the parameters passed to the requirement.
	 *
	 * @param string $component The component name.
	 * @param string $version   The component version.
	 * @param string $operator  The operator to use when comparing version.
	 *
	 * @throws Whip_EmptyProperty       When any of the parameters is empty.
	 * @throws Whip_InvalidOperatorType When the $operator parameter is invalid.
	 * @throws Whip_InvalidType         When any of the parameters is not of the expected type.
	 */
	private function validateParameters( $component, $version, $operator ) {
		if ( empty( $component ) ) {
			throw new Whip_EmptyProperty( 'Component' );
		}

		if ( ! is_string( $component ) ) {
			throw new Whip_InvalidType( 'Component', 'string', $component );
		}

		if ( empty( $version ) ) {
			throw new Whip_EmptyProperty( 'Version' );
		}

		if ( ! is_string( $version ) ) {
			throw new Whip_InvalidType( 'Version', 'string', $version );
		}

		if ( empty( $operator ) ) {
			throw new Whip_EmptyProperty( 'Operator' );
		}

		if ( ! is_string( $operator ) ) {
			throw new Whip_InvalidType( 'Operator', 'string', $operator );
		}

		if ( ! in_array( $operator, array( '=', '==', '===', '<', '>', '<=', '>=' ), true ) ) {
			throw new Whip_InvalidOperatorType( $operator );
		}
	}
}
