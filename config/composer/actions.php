<?php

namespace Yoast\WHIP\Config\Composer;

use Composer\Script\Event;

/**
 * Class to handle Composer actions and events.
 *
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName -- Disabled to allow easier syncing with the same in other plugins.
 * @phpcs:disable Generic.NamingConventions.CamelCapsFunctionName -- Disabled to allow easier syncing with the same in other plugins.
 * @phpcs:disable WordPress.Security.EscapeOutput -- This file is not distributed, so this is fine.
 * @phpcs:disable WordPress.PHP.DiscouragedPHPFunctions -- This file is not distributed, so this is fine.
 */
class Actions {

	/**
	 * Provides a coding standards option choice.
	 *
	 * @param Event $event Composer event.
	 */
	public static function check_coding_standards( Event $event ) {
		$io = $event->getIO();

		$choices = array(
			'1' => array(
				'label'   => 'Check staged files for coding standard warnings & errors.',
				'command' => 'check-staged-cs',
			),
			'2' => array(
				'label'   => 'Check current branch\'s changed files for coding standard warnings & errors.',
				'command' => 'check-branch-cs',
			),
			'3' => array(
				'label'   => 'Check for all coding standard errors.',
				'command' => 'check-cs',
			),
			'4' => array(
				'label'   => 'Check for all coding standard warnings & errors.',
				'command' => 'check-cs-warnings',
			),
			'5' => array(
				'label'   => 'Fix auto-fixable coding standards.',
				'command' => 'fix-cs',
			),
			'6' => array(
				'label'   => 'Verify coding standard violations are below thresholds.',
				'command' => 'check-cs-thresholds',
			),
		);

		$args = $event->getArguments();
		if ( empty( $args ) ) {
			foreach ( $choices as $choice => $data ) {
				$io->write( \sprintf( '%d. %s', $choice, $data['label'] ) );
			}

			$choice = $io->ask( 'What do you want to do? ' );
		}
		else {
			$choice = $args[0];
		}

		if ( isset( $choices[ $choice ] ) ) {
			$event_dispatcher = $event->getComposer()->getEventDispatcher();
			$event_dispatcher->dispatchScript( $choices[ $choice ]['command'] );
		}
		else {
			$io->write( 'Unknown choice.' );
		}
	}

	/**
	 * Runs PHPCS on the files changed in the current branch.
	 *
	 * Used by the composer check-branch-cs command.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param Event $event Composer event that triggered this script.
	 *
	 * @return void
	 */
	public static function check_branch_cs( Event $event ) {
		$branch = 'main';

		$args = $event->getArguments();
		if ( ! empty( $args ) ) {
			$branch = $args[0];
		}

		exit( self::check_cs_for_changed_files( $branch ) );
	}

	/**
	 * Runs PHPCS on changed files compared to some git reference.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param string $compare The git reference.
	 *
	 * @return int Exit code passed from the coding standards check.
	 */
	private static function check_cs_for_changed_files( $compare ) {
		\exec( 'git diff --name-only --diff-filter=d ' . \escapeshellarg( $compare ), $files );

		$php_files = self::filter_files( $files, '.php' );
		if ( empty( $php_files ) ) {
			echo 'No files to compare! Exiting.' . \PHP_EOL;

			return 0;
		}

		/*
		 * In CI, generate both the normal report as well as the checkstyle report.
		 * The normal report will be shown in the actions output and ensures human readable (and colorized!) results there.
		 * The checkstyle report is used to show the results inline in the GitHub code view.
		 */
		$extra_args = ( \getenv( 'CI' ) === false ) ? '' : ' --colors --no-cache --report-full --report-checkstyle=./phpcs-report.xml';
		$command    = \sprintf(
			'composer check-cs-warnings -- %s %s',
			\implode( ' ', \array_map( 'escapeshellarg', $php_files ) ),
			$extra_args
		);
		\system( $command, $exit_code );

		return $exit_code;
	}

	/**
	 * Checks if the CS errors and warnings are below or at thresholds.
	 *
	 * @return void
	 */
	public static function check_cs_thresholds() {
		$in_ci = \getenv( 'CI' );

		echo 'Running coding standards checks, this may take some time.', \PHP_EOL;

		$command = 'composer check-cs-warnings -- -mq --report="YoastCS\\Yoast\\Reports\\Threshold"';
		if ( $in_ci !== false ) {
			// Always show the results in CI in color.
			$command .= ' --colors';
		}
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Non-WP context, this is fine.
		@\exec( $command, $phpcs_output, $return );

		$phpcs_output = \implode( \PHP_EOL, $phpcs_output );
		echo $phpcs_output;

		$above_threshold = true;
		if ( \strpos( $phpcs_output, 'Coding standards checks have passed!' ) !== false ) {
			$above_threshold = false;
		}

		/*
		 * Don't run the branch check in CI/GH Actions as it prevents the errors from being shown inline.
		 * The GH Actions script will run this via a separate script step.
		 */
		if ( $above_threshold === true && $in_ci === false ) {
			echo \PHP_EOL;
			echo 'Running check-branch-cs.', \PHP_EOL;
			echo 'This might show problems on untouched lines. Focus on the lines you\'ve changed first.', \PHP_EOL;
			echo \PHP_EOL;

			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Non-WP context, this is fine.
			@\passthru( 'composer check-branch-cs' );
		}

		exit( ( $above_threshold === true || $return > 2 ) ? $return : 0 );
	}

	/**
	 * Filter files on extension.
	 *
	 * @param array<string> $files     List of files.
	 * @param string        $extension Extension to filter on.
	 *
	 * @return array<string> Filtered list of files.
	 */
	private static function filter_files( array $files, $extension ) {
		return \array_filter(
			$files,
			function ( $file ) use ( $extension ) {
				return \substr( $file, ( 0 - \strlen( $extension ) ) ) === $extension;
			}
		);
	}
}
