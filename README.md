# whip
A WordPress package to nudge users to upgrade their software versions (starting with PHP)

## Requirements

The following versions of PHP are supported:

* PHP 5.2
* PHP 5.3
* PHP 5.4
* PHP 5.5
* PHP 5.6
* PHP 7.0
* PHP 7.1


* The `WPMessagePresenter` requires WordPress or a function called `add_action`, to hook into WordPress.
* The `PHPVersionDetector` requires WordPress or a function called `__`, to translate strings.

## Installation

```bash
$ composer require yoast/whip 
```

## Usage

To require users to have PHP 5.6 or higher and show them a message if this is not the case you can use the following code:

```php
$wpMessagePresenter = new WPMessagePresenter();
$wpMessagePresenter->register_hooks();

$versionMessageControl = new VersionMessageControl(
	new PHPVersionDetector(),
	array( $wpMessagePresenter )
);
$versionMessageControl->requireVersion( '5.6' );
```

There is also a convenient helper function that you can use:

```php
Whip_VersionMessage::require_versions( array(
	'php' => '5.6',
) );
```

## Changelog


## Security

If you discover any security related issues, please email security@yoast.com instead of using the issue tracker.

## Credits

* [Team Yoast](https://github.com/yoast)
