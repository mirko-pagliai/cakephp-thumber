# Thumber

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://travis-ci.org/mirko-pagliai/cakephp-thumber.svg?branch=master)](https://travis-ci.org/mirko-pagliai/cakephp-thumber)
[![Build status](https://ci.appveyor.com/api/projects/status/kmo1kmgqg34y4g87?svg=true)](https://ci.appveyor.com/project/mirko-pagliai/cakephp-thumber)
[![Coverage Status](https://img.shields.io/codecov/c/github/mirko-pagliai/cakephp-thumber.svg?style=flat-square)](https://codecov.io/github/mirko-pagliai/cakephp-thumber)

*cakephp-thumber* is a CakePHP plugin to create thumbnails.  

It uses [intervention/image](https://github.com/Intervention/image) and 
provides:
* `ThumbCreator`, an utility to create thumbnails;
* `ThumbHelper`, a convenient helper that allows you to create thumbnails on 
the fly from your templates.

***

  * [Requirements and supported formats](#requirements-and-supported-formats)
  * [Installation](#installation)
  * [Configuration](#configuration)
    + [Configuration values](#configuration-values)
  * [How to use](#how-to-use)
  * [Testing](#testing)
  * [Versioning](#versioning)

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

    $ composer require --prefer-dist mirko-pagliai/cakephp-thumber

**NOTE**: the package may require one of the latest versions version of CakePHP.  
Instead, the [cakephp3.2](//github.com/mirko-pagliai/cakephp-thumber/tree/cakephp3.2)
branch is compatible with CakePHP versions from 3.2 onwards.  
In this case, you can install the package as well:

    $ composer require --prefer-dist mirko-pagliai/cakephp-thumber:dev-cakephp3.2
    
After installation, you have to edit `APP/config/bootstrap.php` to load the plugin:

    Plugin::load('Thumber', ['bootstrap' => true, 'routes' => true]);

For more information on how to load the plugin, please refer to the 
[Cookbook](http://book.cakephp.org/3.0/en/plugins.html#loading-a-plugin).
    
By default the plugin uses the `APP/tmp/thumbs` directory to save the 
thumbnails. So you have to create the directory and make it writable:

    $ mkdir tmp/thumbs && chmod 775 tmp/thumbs

If you want to use a different directory, read below.

## Configuration
The plugin uses some configuration parameters and you can set them using the 
`\Cake\Core\Configure` class, **before** loading the plugin.

For example, you can do this at the bottom of the file `APP/config/app.php`
of your application.

**Note**: up to the *1.0.4* version, the configuration parameters had the `Thumbs` name as a prefix. Starting with the *1.1.0* version, they have the plugin name (`Thumber`) as a prefix.

### Configuration values

    Configure::write('Thumber.driver', 'imagick');
    
Setting `Thumber.driver`, you can choose which driver to use for the creation of 
thumbnails. Valid values are `imagick` or `gd`.

    Configure::write('Thumber.target', TMP . 'thumbs');
    
Setting `Thumber.target`, you can use another directory where the plugin will 
save thumbnails.

## How to use
See our wiki:
* [How to use the helper](https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-use-the-helper)
* [How to uses the ThumbCreator utility](https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility)


## Testing
The library (`GD` or `Imagick`) to be tested is set by the `tests/bootstrap.php` file, using the
`THUMBER_DRIVER` environment variable. By default, `Imagick` is used.

For example:

    if (!getenv('THUMBER_DRIVER')) {
        putenv('THUMBER_DRIVER=imagick');
    }

    Configure::write('Thumber.driver', getenv('THUMBER_DRIVER'));
    
Moreover, some tests belong to the `imageEquals` group. These tests generate thubnails and compare them with pre-loaded thumbnails (inside `tests/comparing_files/`).  
By default, these tests are not performed, because the images may be different if generated from different environments and systems.

To exclude these tests, you should run:

    vendor/bin/phpunit --exclude-group imageEquals

## Versioning
For transparency and insight into our release cycle and to maintain backward 
compatibility, *Thumber* will be maintained under the 
[Semantic Versioning guidelines](http://semver.org).
