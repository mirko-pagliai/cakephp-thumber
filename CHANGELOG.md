# 1.x branch
## 1.4 branch
# 1.4.1
* removed `Thumber\TestSuite\TestCase::assertFileExtension()` method;
* removed `Thumber\TestSuite\TestCase::assertImageSize()` method;
* `Thumber\TestSuite\TestCase::assertMime()` method renamed as `assertFileMime()`.

# 1.4.0
* removed all methods provided previously by the `ThumbHelper`. These methods
    are now called dynamically by the `__call()` magic method;
* tests have been simplified. The library to be tested is set by the
    `tests/bootstrap.php` file, using the `THUMBER_DRIVER` environment variable.
    By default, Imagick is used;
* full support for Windows, added tests for Appveyor;
* added `Thumber\TestSuite\TestCase::assertThumbUrl()` method;
* now it uses the `mirko-pagliai/php-tools` package. This also replaces
    `mirko-pagliai/reflection`.

## 1.3 branch
# 1.3.1
* added `ThumbCreator::resizeCanvas()` method;
* added `ThumbHelper::resizeCanvas()` and `ThumbHelper::resizeCanvasUrl()` methods. 

# 1.3.0
* the name of the thumbnails (when automatically generated) now contains a
    prefix with the checksum of the path of the original image and a suffix with
    the checksum of all the arguments used to create the thumbnail. Using the
    prefix of a thumbnail, it will be possible to identify the original image
    from which it was generated;
* the `Last-Modified` header is set up and sent to the client. It indicates the
    date and time at which the thumbnail file was modified for the last time;
* added `ThumbManager` and `ThumbShell` classes;
* `resolveFilePath()` method moved from `ThumbCreator` to `ThumbTrait`, because
    this method can be used in different contexts.

## 1.2 branch
# 1.2.0
* updated for intervention/image 2.4;
* some little fixes. This package requires at least CakePHP 3.4.

## 1.1 branch
# 1.1.1
* fixed bug when using similar format name, , as `jpeg` or `tif`;
* added `TestCase::assertFileExtension()` and `TestCase::assertThumbPath()`
    methods;
* added `ThumbTrait`;
* added `Thumber\TestSuite\IntegrationTestCase` class;
* removed `thumbUrl()` global function. Use instead `ThumbTrait::getUrl()`;
* significantly improved and simplified all tests;
* the MIT license has been applied.

# 1.1.0
* configuration parameters have the name of the plugin (`Thumber`) as prefix. So
    now they are `Thumber.driver` and `Thumber.target`;
* the target directory is created automatically, if it does not exist;
* added `rtr()` global function;
* added `THUMBER` constant.

## 1.0 branch
### 1.0.4
* added `ThumbNotFoundException` class. This exception is thrown when you
    request a thumb that is not available;
* methods that have been deprecated with CakePHP 3.4 have been replaced;
* updated for CakePHP 3.4.

### 1.0.3
* little code fixes;
* improved tests.

### 1.0.2
* support for `bmp`, `ico`, `psd` and `tiff` formats;
* checks for formats supported by GD driver.

### 1.0.1
* added `fit()` method for `ThumbCreator` and `ThumbHelper` classes;
* the `save()` method can save thumbnails with a different format;
* the `save()` method has the `quality` option (default: 90);
* by default, the `ThumbHelper` returs "full base" URLs;
* by default, the `ThumbHelper` creates jpg thumbnails.

### 1.0.0
* first release.
