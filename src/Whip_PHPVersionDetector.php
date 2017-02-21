<?php

/**
 * A version detector for PHP
 */
class Whip_PHPVersionDetector implements Whip_VersionDetector {
	const HOST_NAME_KEY = 'WHIP_NAME_OF_HOST';
	const HOST_MESSAGE_KEY = 'WHIP_MESSAGE_FROM_HOST_ABOUT_PHP';

	/**
	 * @var string
	 */
	protected $textdomain;

	/**
	 * PHPVersionDetector constructor.
	 *
	 * @param string $textdomain The textdomain with which to translate the message.
	 */
	public function __construct( $textdomain ) {
		$this->textdomain = $textdomain;
	}

	/**
	 * Detects the version of the installed software
	 *
	 * @return string
	 */
	public function detect() {
		return phpversion();
	}

	/**
	 * Returns the message relevant for an outdated PHP version.
	 *
	 * @returns string The message to show to the user.
	 */
	public function getMessage() {
		$textdomain = $this->textdomain;
		$message = array();

		$nameOfHost = $this->getNameOfHost();
		$messageFromHost = $this->getMessageFromHost();

		$message[] = $this->p( $this->strong( __( 'Your site could be faster and more secure with a newer PHP version.', $textdomain ) ) ) . '<br />';
		$message[] = $this->p( __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP. PHP is the programming language that WordPress and Yoast SEO are built on. The version that is currently used for your site is no longer supported. Newer versions of PHP are both faster and more secure. In fact, your version of PHP no longer receives security updates, which is why we\'re sending you to this notice.', $textdomain ) );
		$message[] = $this->p(  __( 'Hosts have the ability to update your PHP version, but sometimes they don\'t dare to do that because they\'re afraid they\'ll break your site.', $textdomain ) );

		$message[] = $this->p( $this->strong( __( 'To which version should I update?', $textdomain ) ) ) . '<br />';
		$message[] = $this->p( __( 'You should update your PHP version to either 5.6 or to 7.0 or 7.1. On a normal WordPress site, switching to PHP 5.6 should never cause issues. We would however actually recommend you switch to PHP7. There are some plugins that are not ready for PHP7 though, so do some testing first. We have an article on how to test whether that\'s an option for you here. PHP7 is much faster than PHP 5.6. It\'s also the only PHP version still in active development and therefore the better option for your site in the long run.', $textdomain ) );

		if ( $nameOfHost !== '' ) {
			$message[] = $this->strong( sprintf( __( 'A message from %1$s', $textdomain ), $nameOfHost ) ) . '<br />';
			$message[] = $this->p( $messageFromHost );
		}

		$message[] = $this->p( $this->strong( __( 'Can\'t update? Ask your host!', $textdomain ) ) ) . '<br />';
		$message[] = $this->p( __( 'If you cannot upgrade your PHP version yourself, you can send an email to your host. We have examples here. If they don\'t want to upgrade your PHP version, we would suggest you switch hosts. Have a look at one of our recommended WordPress hosting partners, they\'ve all been vetted by our Yoast support team and provide all the features a modern host should provide.', $textdomain ) );

		return implode( $message, "\n" );
	}

	/**
	 * Retrieves the name of the host if set
	 *
	 * @return string
	 */
	public function getNameOfHost() {
		return (string) getenv( self::HOST_NAME_KEY );
	}

	/**
	 * Retrieves the message from the host if set
	 *
	 * @return string
	 */
	public function getMessageFromHost() {
		return (string) getenv( self::HOST_MESSAGE_KEY );
	}

	/**
	 * Wraps a piece of text in HTML strong tags
	 *
	 * @param string $toWrap The text to wrap.
	 * @return string The wrapped text.
	 */
	private function strong( $toWrap ) {
		return '<strong>' . $toWrap . '</strong>';
	}

	/**
	 * Wraps a piece of text in HTML p tags
	 *
	 * @param string $toWrap The text to wrap.
	 * @return string The wrapped text.
	 */
	private function p( $toWrap ) {
		return '<p>' . $toWrap . '</p>';
	}
}
