<?php


interface MessagePresenter {

	/**
	 * Shows a message to the user
	 *
	 * @param string $message The message to show to the user
	 */
	public function show( $message );
}
