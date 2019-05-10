<?php
/**
 * This file is part of cakephp-thumber.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/cakephp-thumber
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @see         https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility
 */
namespace Thumber\Utility;

use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;
use Intervention\Image\Constraint;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use RuntimeException;
use Thumber\ThumbsPathTrait;

/**
 * Utility to create a thumb.
 *
 * Please, refer to the `README` file to know how to use the utility and to
 * see examples.
 */
class ThumbCreator
{
    use ThumbsPathTrait;

    /**
     * `ImageManager` instance
     * @var Intervention\Image\ImageManager
     */
    public $ImageManager;

    /**
     * Arguments that will be used to generate the name of the thumbnail.
     *
     * Every time you call a method that alters the final thumbnail, its
     * arguments must be added to this array, including the name of that method.
     * @var array
     */
    protected $arguments = [];

    /**
     * Callbacks that will be called by the `save()` method to create the
     * thumbnail
     * @var array
     */
    protected $callbacks = [];

    /**
     * Driver name
     * @var string
     */
    protected $driver;

    /**
     * Path of the file from which the thumbnail will be generated
     * @var string
     */
    protected $path;

    /**
     * Path of the generated thumbnail
     * @var string
     */
    protected $target;

    /**
     * Construct.
     * It sets the file path and extension.
     * @param string $path Path of the image from which to create the
     *  thumbnail. It can be a relative path (to APP/webroot/img), a full path
     *  or a remote url
     * @return \Thumber\Utility\ThumbCreator
     * @uses $ImageManager
     * @uses $arguments
     * @uses $driver
     * @uses $path
     */
    public function __construct($path)
    {
        $this->driver = Configure::readOrFail('Thumber.driver');
        $this->ImageManager = new ImageManager(['driver' => $this->driver]);
        $this->path = $this->resolveFilePath($path);
        $this->arguments[] = $this->path;

        return $this;
    }

    /**
     * Internal method to get default options for the `save()` method
     * @param array $options Passed options
     * @param string|null $path Path to use
     * @return array Passed options added to the default options
     * @uses $path
     */
    protected function getDefaultSaveOptions($options, $path = null)
    {
        $options += [
            'format' => get_extension($path ?: $this->path),
            'quality' => 90,
            'target' => false,
        ];

        //Fixes some formats
        $options['format'] = preg_replace(['/^jpeg$/', '/^tif$/'], ['jpg', 'tiff'], $options['format']);

        return $options;
    }

    /**
     * Gets an `Image` instance
     * @return \Intervention\Image\Image
     * @throws RuntimeException
     * @uses $ImageManager
     * @uses $path
     */
    protected function getImageInstance()
    {
        //Tries to create the image instance
        try {
            $imageInstance = $this->ImageManager->make($this->path);
        } catch (NotReadableException $e) {
            $message = __d('thumber', 'Unable to read image from file `{0}`', rtr($this->path));

            if ($e->getMessage() == 'Unsupported image type. GD driver is only able to decode JPG, PNG, GIF or WebP files.') {
                $message = __d('thumber', 'Image type `{0}` is not supported by this driver', mime_content_type($this->path));
            }

            throw new RuntimeException($message);
        }

        return $imageInstance;
    }

    /**
     * Builds and returns the url for the generated thumbnail
     * @param bool $fullBase If `true`, the full base URL will be prepended to
     *  the result
     * @return string
     * @since 1.5.1
     * @throws InvalidArgumentException
     * @uses $target
     */
    public function getUrl($fullBase = true)
    {
        is_true_or_fail(!empty($this->target), __d(
            'thumber',
            'Missing path of the generated thumbnail. Probably the `{0}` method has not been invoked',
            'save()'
        ), InvalidArgumentException::class);

        return Router::url(['_name' => 'thumb', base64_encode(basename($this->target))], $fullBase);
    }

    /**
     * Crops the image, cutting out a rectangular part of the image.
     *
     * You can define optional coordinates to move the top-left corner of the
     *  cutout to a certain position.
     * @param int|null $width Required width
     * @param int|null $heigth Required heigth
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#crop
     * @uses $arguments
     * @uses $callbacks
     */
    public function crop($width = null, $heigth = null, array $options = [])
    {
        $heigth = $heigth ?: $width;
        $width = $width ?: $heigth;

        //Sets default options
        $options += ['x' => null, 'y' => null];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function (Image $imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->crop($width, $heigth, $options['x'], $options['y']);
        };

        return $this;
    }

    /**
     * Resizes the image, combining cropping and resizing to format image in a
     *  smart way. It will find the best fitting aspect ratio on the current
     *  image automatically, cut it out and resize it to the given dimension
     * @param int|null $width Required width
     * @param int|null $heigth Required heigth
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#fit
     * @uses $arguments
     * @uses $callbacks
     */
    public function fit($width = null, $heigth = null, array $options = [])
    {
        $heigth = $heigth ?: $width;
        $width = $width ?: $heigth;

        //Sets default options
        $options += ['position' => 'center', 'upsize' => true];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function (Image $imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->fit($width, $heigth, function (Constraint $constraint) use ($options) {
                if ($options['upsize']) {
                    $constraint->upsize();
                }
            }, $options['position']);
        };

        return $this;
    }

    /**
     * Resizes the image
     * @param int|null $width Required width
     * @param int|null $heigth Required heigth
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#resize
     * @uses $arguments
     * @uses $callbacks
     */
    public function resize($width = null, $heigth = null, array $options = [])
    {
        //Sets default options
        $options += ['aspectRatio' => true, 'upsize' => true];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function (Image $imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->resize($width, $heigth, function (Constraint $constraint) use ($options) {
                if ($options['aspectRatio']) {
                    $constraint->aspectRatio();
                }

                if ($options['upsize']) {
                    $constraint->upsize();
                }
            });
        };

        return $this;
    }

    /**
     * Resizes the boundaries of the current image to given width and height. An
     *  anchor can be defined to determine from what point of the image the
     *  resizing is going to happen. Set the mode to relative to add or subtract
     *  the given width or height to the actual image dimensions. You can also
     *  pass a background color for the emerging area of the image
     * @param int|null $width Required width
     * @param int|null $heigth Required heigth
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#resizecanvas
     * @since 1.3.1
     * @uses $arguments
     * @uses $callbacks
     */
    public function resizeCanvas($width, $heigth = null, array $options = [])
    {
        //Sets default options
        $options += ['anchor' => 'center', 'relative' => false, 'bgcolor' => '#ffffff'];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function (Image $imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->resizeCanvas($width, $heigth, $options['anchor'], $options['relative'], $options['bgcolor']);
        };

        return $this;
    }

    /**
     * Saves the thumbnail and returns its path
     * @param array $options Options for saving
     * @return string Thumbnail path
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#save
     * @throws RuntimeException
     * @uses getDefaultSaveOptions()
     * @uses getImageInstance()
     * @uses $arguments
     * @uses $callbacks
     * @uses $driver
     * @uses $path
     * @uses $target
     */
    public function save(array $options = [])
    {
        is_true_or_fail($this->callbacks, __d('thumber', 'No valid method called before the `{0}` method', __FUNCTION__), RuntimeException::class);

        $options = $this->getDefaultSaveOptions($options);
        $target = $options['target'];

        if (!$target) {
            $this->arguments[] = [$this->driver, $options['format'], $options['quality']];

            $target = sprintf('%s_%s.%s', md5($this->path), md5(serialize($this->arguments)), $options['format']);
        } else {
            $optionsFromTarget = $this->getDefaultSaveOptions([], $target);
            $options['format'] = $optionsFromTarget['format'];
        }

        $target = Folder::isAbsolute($target) ? $target : $this->getPath($target);
        $File = new File($target);

        //Creates the thumbnail, if this does not exist
        if (!$File->exists()) {
            $imageInstance = $this->getImageInstance();

            //Calls each callback
            foreach ($this->callbacks as $callback) {
                call_user_func($callback, $imageInstance);
            }

            $content = $imageInstance->encode($options['format'], $options['quality']);
            $imageInstance->destroy();

            is_true_or_fail($File->Folder->pwd() && $File->write($content), __d('thumber', 'Unable to create file `{0}`', rtr($target)), RuntimeException::class);
            $File->close();
        }

        //Resets arguments and callbacks
        $this->arguments = $this->callbacks = [];

        return $this->target = $target;
    }
}
