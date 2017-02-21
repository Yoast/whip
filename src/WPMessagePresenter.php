<?php


class WPMessagePresenter implements MessagePresenter {

	/**
	 * @var string[]
	 */
	protected $messages;

	/**
	 * Registers hooks to WordPress. This is a seperate function so you can
	 * control when the hooks are registered.
	 */
	public function register_hooks() {
		add_action( 'admin_notices', array( $this, 'renderMessage' ) );
	}

	/**
	 * Shows a message to the user
	 *
	 * @param string $message The message to show to the user.
	 */
	public function show( $message ) {
		$this->addMessage( $message );
	}

	public function renderMessage() {
		if ( empty( $GLOBALS['whip_messages'] ) ) {
			return;
		}

		$message = $this->getLatestMessage();

		?>
        <div class="error">
			<?php echo $this->kses( $message ); ?>
        </div>
		<?php
	}

	public function kses( $message ) {
		return wp_kses( $message, array(
			'a'      => array(
				'href' => true,
			),
			'strong' => true,
			'p'      => true,
		) );
	}

	/**
	 * Makes sure the data structure for the messages is present in the globals
	 */
	public function ensureDataStructure() {
		if ( ! array_key_exists( 'whip_messages', $GLOBALS ) ) {
			$GLOBALS['whip_messages'] = array();
		}
	}

	/**
	 * Adds a message to the WHIP messages global. It will be added with the
	 * version of WHIP so we can always show the message from the latest
	 * version of WHIP.
	 *
	 * @param string $message The message to add to the global.
	 */
	public function addMessage( $message ) {
	    $whipVersion = require dirname( __FILE__ ) . '/version.php';

		$this->ensureDataStructure();

		$GLOBALS['whip_messages'][ $whipVersion ] = $message;
	}

	/**
	 * Retrieves the latest message from the messages global
	 *
     * @return string The latest message from the messages global.
	 */
	public function getLatestMessage() {
		$messages = $GLOBALS['whip_messages'];
		unset( $GLOBALS['whip_messages'] );

		uksort( $messages, 'version_compare' );
		$latestMessage = array_pop( $messages );

		return $latestMessage;
	}
}
