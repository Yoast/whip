<?php


/**
 * A class to dismiss messages
 */
final class Whip_MessageDismisser {

	/** @var Whip_DissmisStorage */
	protected $storage;

	/** @var array */
	private $dismissed = array();

	/** @var int */
	private $expirationDate;

	/**
	 * Whip_MessageDismisser constructor.
	 *
	 * @param int                 $expirationDate
	 * @param Whip_DissmisStorage $storage
	 */
	public function __construct( $expirationDate, Whip_DissmisStorage $storage ) {
		$this->expirationDate = $expirationDate;
		$this->storage        = $storage;

		$this->setDismissed();
	}

	/**
	 * Dismisses the message for the given component/version.
	 *
	 * @param string $component       The component to dismiss.
	 * @param string $version_compare The version comparison string.
	 */
	public function dismiss( $component, $version_compare ) {
		if ( ! isset( $this->dismissed[ $component ] ) ) {
			$this->dismissed[ $component ] = array();
		}

		$this->dismissed[ $component ][ $version_compare ] = $this->expirationDate;

		$this->storage->save( $this->dismissed );
	}

	/**
	 * Filters the requirement based on the requirements to filter.
	 *
	 * @param string $versionComparison The version comparison.
	 * @param string $component         The component name.
	 *
	 * @return bool
	 */
	public function isDismissed( $versionComparison, $component ) {
		if ( ! isset( $this->dismissed[ $component ] ) ) {
			return true;
		}

		if ( ! isset( $this->dismissed[ $component ][ $versionComparison ] ) ) {
			return true;
		}

		return ! $this->isExpired( $this->dismissed[ $component ][ $versionComparison ] );
	}

	/**
	 * Sets the dismissed values for current request.
	 */
	private function setDismissed() {
		$dismissedOption = $this->storage->get();
		$this->dismissed = $this->filterExpired( $dismissedOption );
		if ( $this->dismissed !== $dismissedOption ) {
			$this->storage->save( $this->dismissed );
		}
	}

	/**
	 * Filters all expires dismissed values.
	 *
	 * @param array $dismissed The dismissed values.
	 *
	 * @return array
	 */
	private function filterExpired( array $dismissed ) {
		foreach( $dismissed as $component ) {
			foreach( $component as $versionCompare => $expirationDate ) {
				if ( $this->isExpired( $expirationDate ) ) {
					unset( $dismissed[ $component ][ $versionCompare ] );
				}
			}

			if ( empty( $dismissed[ $component ] ) ) {
				unset( $dismissed[ $component ] );
			}
		}

		return $dismissed;
	}

	/**
	 * Checks if the dismissed value has been expired.
	 *
	 * @param string $expirationDate The expiration date.
	 *
	 * @return bool True when expired.
	 */
	private function isExpired( $expirationDate ) {
		return $expirationDate <= time();
	}
}
