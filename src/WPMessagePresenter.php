<?php


class WPMessagePresenter implements MessagePresenter {

	/**
	 * @var string[]
	 */
	protected $messages;

	public function register_hooks() {
		add_action( 'admin_notices', array( $this, 'renderMessage' ) );
	}

	/**
	 * Shows a message to the user
	 *
	 * @param string $message The message to show to the user
	 */
	public function show( $message ) {
		$this->messages[] = $message;
	}

	public function renderMessage() {
		?>
			<div class="error">
				<?php echo $this->kses( $this->messages[0] ); ?>
			</div>
		<?php
	}

	public function kses( $message ) {
		return wp_kses( $message, array(
			'a' => array(
				'href' => true,
			),
			'strong' => true,
			'p' => true,
		) );
	}
}
