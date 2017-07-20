<?php

/**
 * Main class to filter requirements.
 */
class Whip_RequirementsFilter {

	/**
	 * @var int
	 */
	private $expirationDate;

	/**
	 * @var array
	 */
	private $requirementsToFilter;

	/**
	 * Whip_RequirementsChecker constructor.=
	 *
	 * @param integer $expirationDate       The expiration date.
	 * @param array   $requirementsToFilter The requirements to filter.
	 */
	public function __construct( $expirationDate, array $requirementsToFilter = array() ) {
		$this->expirationDate       = $expirationDate;
		$this->requirementsToFilter = $requirementsToFilter;
	}

	/**
	 * Filters the requirements.
	 * @param array $requirements The requirements.
	 *
	 * @return array The filtered requirements.
	 */
	public function filter( $requirements ) {
		return array_filter( $requirements, array( $this, 'filterRequirement' ), ARRAY_FILTER_USE_BOTH );
	}

	/**
	 * Filters the requirement based on the requirements to filter.
	 *
	 * @param string $versionComparison The version comparison.
	 * @param string $component         The component name.
	 *
	 * @return bool
	 */
	public function filterRequirement( $versionComparison, $component ) {
		if ( ! isset( $this->requirementsToFilter[ $component ] ) ) {
			return true;
		}

		if ( ! isset( $this->requirementsToFilter[ $component ][ $versionComparison ] ) ) {
			return true;
		}

		return $this->requirementsToFilter[ $component ][ $versionComparison ] > $this->expirationDate;
	}

}
