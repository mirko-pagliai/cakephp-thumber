# 1.x branch
## 1.1 branch
# 1.1.1
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
