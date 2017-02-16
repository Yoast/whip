<?php

/**
 * A version detector for PHP
 */
class PHPVersionDetector implements VersionDetector {

	/**
	 * Detects the version of the installed software
	 *
	 * @return string
	 */
	public function detect() {
		return phpversion();
	}
}
