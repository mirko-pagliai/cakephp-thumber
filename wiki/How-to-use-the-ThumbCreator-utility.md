`ThumbCreator` is an utility to create thumbnails.

* [Basic operation](#basic-operation)
* [Methods](#methods)
   * [<code>crop()</code>](#crop)
   * [<code>fit()</code>](#fit)
   * [<code>resize()</code>](#resize)
   * [<code>resizeCanvas()</code>](#resizecanvas)
* [Save the thumbnail](#save-the-thumbnail)
   * [<code>save()</code>](#save)

***

# Basic operation
To create a thumbnail, you have to follow three steps:

**1) Instantiate the class**  
Instantiate the class, passing the path of a file as a parameter to the constructor.  
Example:
```php
$thumber = new ThumbCreator('400x400.gif');
```

The path can be:
* a relative path (to `APP/webroot/img`);
* a relative path using [plugin syntax](http://book.cakephp.org/3.0/en/appendices/glossary.html#term-plugin-syntax);
* a full path;
* a remote url.

**2) Call one (or more) optional methods**  
Call one (or more) optional methods that alter the original image.  
Example:
```php
$thumber = new ThumbCreator('400x400.gif');
$thumber->resize(200);
```
See the [methods list](https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#methods) below.

**3) Save the thumbnail**  
Save the thumbnail, calling the `save()` method.  
Example:
```php
$thumber = new ThumbCreator('400x400.gif');
$thumber->resize(200);
$thumb = $thumber->save();
```
Note that the `save()` method returns the path of the thumbnail.  
See [how to use the `save()` method](https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#save-the-thumbnail-1) below.

# Methods
You can call one (or more) methods that alter the original image.

## `crop()`
```php
crop(int $width = null, int $heigth = null, array $options = [])
```
It crops the image, cutting out a rectangular part of the image.  
You can define optional coordinates to move the top-left corner of the cutout to a certain position.

**Parameters for `crop()`**  
*int $width*  
Required width

*int $width*  
Required heigth

*array $options*
Options for the thumbnail. You can use:  
* `x` (`int`, x-coordinate of the top-left corner if the rectangular cutout. By default the rectangular part will be centered on the current image);
* `y` (`int`, y-coordinate of the top-left corner if the rectangular cutout. By default the rectangular part will be centered on the current image).

***

## `fit()`
```php
fit(int $width = null, int $heigth = null, array $options = [])
```
It resizes the image, combining cropping and resizing to format image in a smart way. It will find the best fitting aspect ratio on the current image automatically, cut it out and resize it to the given dimension.

**Parameters for `fit()`**  
*int $width*  
Required width

*int $width*  
Required heigth

*array $options*. You can use:  
* `upsize` (`bool`, keep image from being upsized, default `true`);
* `position` (`string`, position where cutout will be positioned. By default the best fitting aspect ration is centered. For possibile values, see [here](http://image.intervention.io/api/fit)).

***

## `resize()`
```php
resize(int $width = null, int $heigth = null, array $options = [])
```
It resizes the image.

**Parameters for `resize()`**  
*int $width*  
Required width

*int $width*  
Required heigth

*array $options*. You can use:  
* `aspectRatio` (`bool`, constraint the current aspect-ratio if the image, default `true`);
* `upsize` (`bool`, keep image from being upsized, default `true`).

***

## `resizeCanvas()`
```php
resizeCanvas(int $width, int $heigth = null, array $options = [])
```
It resizes the boundaries of the current image to given width and height. An anchor can be defined to determine from what point of the image the resizing is going to happen. Set the mode to relative to add or subtract the  pass a background color for the emerging area of the image

**Parameters for `resizeCanvas()`**  
*int $width*  
Required width

*int $width*  
Required heigth

*array $options*. You can use:  
* `anchor` (`string`, the point from where the image resizing is going to happen. Valid values: `top-left`, `top`, `top-right`, `left`, `center`, `right`, `bottom-left`, `bottom`, `bottom-right`. Default `center`);
* `relative` (`bool`, determine that the resizing is going to happen in relative mode. Meaning that the values of width or height will be added or substracted from the current height of the image. Default `false`);
* `anchor` (`string`, the point from where the image resizing is going to happen. Valid values: `top-left`, `top`, `top-right`, `left`, `center`, `right`, `bottom-left`, `bottom`, `bottom-right`. Default `center`);
* `bgcolor` (`string`, a background color for the new areas of the image. Default `#ffffff`).

# Save the thumbnail
## `save()`
```php
save(array $options = [])
```
Saves the thumbnail and returns its path.

**Parameters for `save()`**  
*array $options*. You can use:  
* `format` (`string`, thumbnail format. Valid values: `bmp`, `gif`, `ico`, `jpg`, `png`, `psd`, `tiff`. By default, it uses the same format of the original image);
* `quality` (`int`, define optionally the quality of the image, from 0 (poor quality, small file) to 100 (best quality, big file). Quality is only for the `jpg` format. The default value is 90);
* `target` (`string`, custom full path target for the thumbnail. By default, the path is automatically set).