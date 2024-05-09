`ThumbHelper` allows you to create thumbnails on the fly from your templates.

Before you start to use it, remember that you have to [load the helper](http://book.cakephp.org/3.0/en/views/helpers.html#configuring-helpers) inside the `AppView::initialize()` method:

```php
$this->loadHelper('Thumber/Cake.Thumb');
```

***

**Summary**

* [<code>crop()</code> and <code>cropUrl()</code>](#crop-and-cropurl)
* [<code>fit()</code> and <code>fitUrl()</code>](#fit-and-fiturl)
* [<code>resize()</code> and <code>resizeUrl()</code>](#resize-and-resizeurl)
* [<code>resizeCanvas()</code> and <code>resizeCanvasUrl()</code>](#resizecanvas-and-resizecanvasurl)

***

## `crop()` and `cropUrl()`
```php
crop(string $path, array $params = [], array $options = [])
```
It creates a thumbnail, cutting out a rectangular part of the image, and returns a formatted `img` element.  
You can define optional coordinates to move the top-left corner of the cutout to a certain position.
```php
cropUrl(string $path, array $params = [], array $options = [])
```
As for `crop()` method, but it returns the thumbnail url.

**Parameters for `crop()` and `cropUrl()`**  
*string $path*  
Path of the image from which to create the thumbnail. It can be:
* a relative path (to `APP/webroot/img`);
* a relative path using [plugin syntax](http://book.cakephp.org/3.0/en/appendices/glossary.html#term-plugin-syntax);
* a full path;
* a remote url.

*array $params*  
Parameters for creating the thumbnail. You can use:
* `height` (`int`);
* `width` (`int`);
* `format` (`string`, thumbnail format. Valid values: `bmp`, `gif`, `ico`, `jpg`, `png`, `psd`, `tiff`. Default `jpg`);
* `x` (`int`, x-coordinate of the top-left corner if the rectangular cutout. By default the rectangular part will be centered on the current image);
* `y` (`int`, y-coordinate of the top-left corner if the rectangular cutout. By default the rectangular part will be centered on the current image).

*array $options* optional   
Array of HTML attributes for the `img` element, as for the method `HtmlHelper::image()`.  
In addition, you can use the `fullBase` option.

## `fit()` and `fitUrl()`
```php
fit(string $path, array $params = [], array $options = [])
```
It creates a thumbnail, combining cropping and resizing to format image in a smart way, and returns a formatted `img` element.  
This method will find the best fitting aspect ratio on the current image automatically, cuts it out and resizes it to the given dimension.
```php
fitUrl(string $path, array $params = [], array $options = [])
```
As for `fit()` method, but it returns the thumbnail url.

**Parameters for `fit()` and `fitUrl()`**  
*string $path*  
Path of the image from which to create the thumbnail. It can be:
* a relative path (to `APP/webroot/img`);
* a relative path using [plugin syntax](http://book.cakephp.org/3.0/en/appendices/glossary.html#term-plugin-syntax);
* a full path;
* a remote url.

*array $params*  
Parameters for creating the thumbnail. You can use:
* `height` (`int`);
* `width` (`int`);
* `format` (`string`, thumbnail format. Valid values: `bmp`, `gif`, `ico`, `jpg`, `png`, `psd`, `tiff`. Default `jpg`);
* `upsize` (`bool`, keep image from being upsized. Default `true`);
* `position` (`string`, position where cutout will be positioned. By default the best fitting aspect ration is centered. For possibile values, see [here](http://image.intervention.io/api/fit)).

*array $options* optional   
Array of HTML attributes for the `img` element, as for the method `HtmlHelper::image()`.  
In addition, you can use the `fullBase` option.

## `resize()` and `resizeUrl()`
```php
resize(string $path, array $params = [], array $options = [])
```
Creates a resized thumbnail and returns a formatted `img` element.
```php
resizeUrl(string $path, array $params = [], array $options = [])
```
As for `resize()` method, but it returns the thumbnail url.

**Parameters for `resize()` and `resizeUrl()`**  
*string $path*  
Path of the image from which to create the thumbnail. It can be:
* a relative path (to `APP/webroot/img`);
* a relative path using [plugin syntax](http://book.cakephp.org/3.0/en/appendices/glossary.html#term-plugin-syntax);
* a full path;
* a remote url.

*array $params*  
Parameters for creating the thumbnail. You can use:
* `height` (`int`);
* `width` (`int`);
* `format` (`string`, thumbnail format. Valid values: `bmp`, `gif`, `ico`, `jpg`, `png`, `psd`, `tiff`. Default `jpg`);
* `aspectRatio` (`bool`, constraint the current aspect-ratio if the image. Default `true`);
* `upsize` (`bool`, keep image from being upsized. Default `true`).

*array $options* optional   
Array of HTML attributes for the `img` element, as for the method `HtmlHelper::image()`.  
In addition, you can use the `fullBase` option.

## `resizeCanvas()` and `resizeCanvasUrl()`
```php
resizeCanvas(string $path, array $params = [], array $options = [])
```
Creates a resized canvas thumbnail and returns a formatted `img` element.
```php
resizeCanvasUrl(string $path, array $params = [], array $options = [])
```
As for `resizeCanvas()` method, but it returns the thumbnail url.

**Parameters for `resizeCanvas()` and `resizeCanvasUrl()`**  
*string $path*  
Path of the image from which to create the thumbnail. It can be:
* a relative path (to `APP/webroot/img`);
* a relative path using [plugin syntax](http://book.cakephp.org/3.0/en/appendices/glossary.html#term-plugin-syntax);
* a full path;
* a remote url.

*array $params*  
Parameters for creating the thumbnail. You can use:
* `height` (`int`);
* `width` (`int`);
* `format` (`string`, thumbnail format. Valid values: `bmp`, `gif`, `ico`, `jpg`, `png`, `psd`, `tiff`. Default `jpg`);
* `anchor` (`string`, the point from where the image resizing is going to happen. Valid values: `top-left`, `top`, `top-right`, `left`, `center`, `right`, `bottom-left`, `bottom`, `bottom-right`. Default `center`);
* `relative` (`bool`, determine that the resizing is going to happen in relative mode. Meaning that the values of width or height will be added or substracted from the current height of the image. Default `false`);
* `bgcolor` (`string`, a background color for the new areas of the image. Default `#ffffff`).

*array $options* optional   
Array of HTML attributes for the `img` element, as for the method `HtmlHelper::image()`.  
In addition, you can use the `fullBase` option.