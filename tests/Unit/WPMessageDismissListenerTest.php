<?php

namespace Yoast\WHIP\Tests\Unit;

use Yoast\WHIPv2\Whip_WPMessageDismissListener;

/**
 * Message Dismiss Listener unit tests.
 *
 * @coversDefaultClass Whip_WPMessageDismissListener
 *
 * @phpcs:disable Yoast.NamingConventions.ObjectNameDepth.MaxExceeded -- Acronym throws the count off.
 */
final class WPMessageDismissListenerTest extends TestCase {

	/**
	 * Tests the listen method.
	 *
	 * @covers \Yoast\WHIPv2\Whip_WPMessageDismissListener::listen
	 *
	 * @dataProvider listenProvider
	 *
	 * @param string $action           The action to test.
	 * @param string $nonce            The nonce to test.
	 * @param int    $verifyNonceTimes The times to call wp_verify_nonce.
	 * @param bool   $isCorrectNonce   Whether the nonce is correct.
	 * @param int    $dismissTimes     The times to call dismiss.
	 *
	 * @return void
	 */
	public function testDismiss( $action, $nonce, $verifyNonceTimes, $isCorrectNonce, $dismissTimes ) {
		$dismisser = $this->getMockBuilder( '\Yoast\WHIPv2\Whip_MessageDismisser' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new Whip_WPMessageDismissListener( $dismisser );

		$_GET['action'] = $action;
		$_GET['nonce']  = $nonce;

		$dismisser->expects( $this->exactly( $verifyNonceTimes ) )
				->method( 'verifyNonce' )
				->with( $nonce, $action )
				->willReturn( $isCorrectNonce );

		$dismisser->expects( $this->exactly( $dismissTimes ) )
				->method( 'dismiss' );

		$instance->listen();
	}

	/**
	 * Data provider for testDismiss.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function listenProvider() {
		return array(
			'correct action and nonce' => array(
				'action'                => Whip_WPMessageDismissListener::ACTION_NAME,
				'nonce'                 => 'the_right_nonce',
				'verifyNonceTimes'      => 1,
				'isCorrectNonce'        => true,
				'dismissTimes'          => 1,
			),
			'incorrect action correct nonce' => array(
				'action'                => 'wrong_action',
				'nonce'                 => 'the_right_nonce',
				'verifyNonceTimes'      => 0,
				'isCorrectNonce'        => false,
				'dismissTimes'          => 0,
			),
			'correct action incorrect nonce' => array(
				'action'                => Whip_WPMessageDismissListener::ACTION_NAME,
				'nonce'                 => 'wrong_nonce',
				'verifyNonceTimes'      => 1,
				'isCorrectNonce'        => false,
				'dismissTimes'          => 0,
			),
			'incorrect action and nonce' => array(
				'action'                => 'wrong_action',
				'nonce'                 => 'wrong_nonce',
				'verifyNonceTimes'      => 0,
				'isCorrectNonce'        => false,
				'dismissTimes'          => 0,
			),
		);
	}
}
