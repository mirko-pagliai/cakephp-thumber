# cakephp-thumber

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://travis-ci.org/mirko-pagliai/cakephp-thumber.svg?branch=master)](https://travis-ci.org/mirko-pagliai/cakephp-thumber)
[![Build status](https://ci.appveyor.com/api/projects/status/kmo1kmgqg34y4g87?svg=true)](https://ci.appveyor.com/project/mirko-pagliai/cakephp-thumber)
[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-thumber/branch/master/graph/badge.svg)](https://codecov.io/gh/mirko-pagliai/cakephp-thumber)
[![CodeFactor](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-thumber/badge)](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-thumber)

*cakephp-thumber* is a CakePHP plugin to create thumbnails.

It uses [intervention/image](https://github.com/Intervention/image) and
provides:
* `ThumbCreator`, an utility to create thumbnails;
* `ThumbHelper`, a convenient helper that allows you to create thumbnails on
the fly from your templates.

Starting from `1.8.0` release, much of the code has been moved into the
[php-thumber](https://github.com/mirko-pagliai/php-thumber) package, so that it
becomes a php library independent from CakePHP.
This plugin continues to exist and all classes and methods remain unchanged, but
it only includes the code closely related to CakePHP (commands, helpers,
middlewares, etc).
The namespace prefix is now `Thumber\Cake` and no longer `Thumber`.

Did you like this plugin? Its development requires a lot of time for me.
Please consider the possibility of making [a donation](//paypal.me/mirkopagliai):
even a coffee is enough! Thank you.

[![Make a donation](https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_carte.jpg)](//paypal.me/mirkopagliai)

***

*   [Requirements and supported formats](#requirements-and-supported-formats)
*   [Installation](#installation)
    + [Installation on older CakePHP and PHP versions](#installation-on-older-cakephp-and-php-versions)
*   [Configuration](#configuration)
    + [Configuration values](#configuration-values)
*   [How to use](#how-to-use)
*   [Testing](#testing)
*   [Versioning](#versioning)

## Requirements and supported formats
*cakephp-thumber* requires GD Library (>=2.0) **or** Imagick PHP extension
(>=6.5.7).
It's **highly preferable** to use Imagick, because It provides better
performance and a greater number of supported formats.

Supported formats may vary depending on the library used.

|         | JPEG | PNG | GIF | TIF | BMP | ICO | PSD |
|---------|------|-----|-----|-----|-----|-----|-----|
| GD      | Yes  | Yes | Yes | No  | No  | No  | No  |
| Imagick | Yes  | Yes | Yes | Yes | Yes | Yes | Yes |

For more information about supported format, please refer to the
[Intervention Image documentation](http://image.intervention.io/getting_started/formats).

## Installation
You can install the plugin via composer:
```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-thumber
```

Then you have to load the plugin. For more information on how to load the plugin,
please refer to the [Cookbook](//book.cakephp.org/4.0/en/plugins.html#loading-a-plugin).

Simply, you can execute the shell command to enable the plugin:
```bash
bin/cake plugin load Thumber/Cake
```
This would update your application's bootstrap method.

By default the plugin uses the `APP/tmp/thumbs` directory to save the
thumbnails. So you have to create the directory and make it writable:

```bash
$ mkdir tmp/thumbs && chmod 775 tmp/thumbs
```

If you want to use a different directory, read the [Configuration](#configuration) section.

### Installation on older CakePHP and PHP versions
Recent packages and the master branch require at least CakePHP 4.0 and PHP 7.2.
Instead, the [cakephp3](//github.com/mirko-pagliai/cakephp-thumber/tree/cakephp3) branch
requires at least PHP 5.6.

In this case, you can install the package as well:
```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-thumber:dev-cakephp3
```

Note that the `cakephp3` branch will no longer be updated as of May 7, 2021,
except for security patches, and it matches the
[1.9.4](//github.com/mirko-pagliai/cakephp-thumber/releases/tag/1.9.4) version.

## Configuration
The plugin uses some configuration parameters and you can set them using the
`\Cake\Core\Configure` class, **before** loading the plugin.

For example, you can do this at the bottom of the file `APP/config/app.php`
of your application.

### Configuration values
```php
Configure::write('Thumber.driver', 'imagick');
```
Setting `Thumber.driver`, you can choose which driver to use for the creation of
thumbnails. Valid values are `imagick` or `gd`.

```php
Configure::write('Thumber.target', TMP . 'thumbs');
```

Setting `Thumber.target`, you can use another directory where the plugin will
save thumbnails.

## How to use
See our wiki:
*   [How to use the helper](https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-use-the-helper)
*   [How to use the ThumbCreator utility](https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-use-the-ThumbCreator-utility)

And refer to our [API](//mirko-pagliai.github.io/cakephp-thumber).

## Testing
The library (`GD` or `Imagick`) to be tested is set by the `tests/bootstrap.php` file, using the
`THUMBER_DRIVER` environment variable. By default, `Imagick` is used.

For example:
```php
if (!getenv('THUMBER_DRIVER')) {
    putenv('THUMBER_DRIVER=imagick');
}

Configure::write('Thumber.driver', getenv('THUMBER_DRIVER'));
```

Moreover, some tests belong to the `imageEquals` group. These tests generate thubnails and compare them with pre-loaded thumbnails (inside `tests/comparing_files/`).
By default, these tests are not performed, because the images may be different if generated from different environments and systems.

To exclude these tests, you should run:
```bash
vendor/bin/phpunit --exclude-group imageEquals
```

## Versioning
For transparency and insight into our release cycle and to maintain backward
compatibility, *Thumber* will be maintained under the
[Semantic Versioning guidelines](http://semver.org).
