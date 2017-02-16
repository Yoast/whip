<?php


interface VersionDetector {

	/**
	 * Detects the version of the installed software
	 *
	 * @return string
	 */
	public function detect();
}
