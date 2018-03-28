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

use Cake\Filesystem\Folder;
use Cake\Network\Exception\InternalErrorException;
use Intervention\Image\Constraint;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use RuntimeException;
use Thumber\ThumbTrait;

/**
 * Utility to create a thumb.
 *
 * Please, refer to the `README` file to know how to use the utility and to
 * see examples.
 */
class ThumbCreator
{
    use ThumbTrait;

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
     * File path
     * @var string
     */
    protected $path;

    /**
     * Construct.
     * It sets the file path and extension.
     * @param string $path Path of the image from which to create the
     *  thumbnail. It can be a relative path (to APP/webroot/img), a full path
     *  or a remote url
     * @return \Thumber\Utility\ThumbCreator
     * @uses $arguments
     * @uses $path
     */
    public function __construct($path)
    {
        $this->path = $this->resolveFilePath($path);
        $this->arguments[] = $this->path;

        return $this;
    }

    /**
     * Internal method to get default options for the `save()` method
     * @param array $options Passed options
     * @return array Passed options added to the default options
     * @uses $path
     */
    protected function getDefaultSaveOptions($options)
    {
        $options += ['format' => $this->getExtension($this->path), 'quality' => 90, 'target' => false];

        //Fixes the name of some similar formats
        if ($options['format'] === 'jpeg') {
            $options['format'] = 'jpg';
        } elseif ($options['format'] === 'tif') {
            $options['format'] = 'tiff';
        }

        return $options;
    }

    /**
     * Gets an `Image` instance
     * @return \Intervention\Image\Image
     * @throws RuntimeException
     * @uses $path
     */
    protected function getImageInstance()
    {
        //Tries to create the image instance
        try {
            $imageInstance = (new ImageManager(['driver' => $this->getDriver()]))
                ->make($this->path);
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
     * Crops the image, cutting out a rectangular part of the image.
     *
     * You can define optional coordinates to move the top-left corner of the
     *  cutout to a certain position.
     * @param int $width Required width
     * @param int $heigth Required heigth
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#crop
     * @uses $arguments
     * @uses $callbacks
     */
    public function crop($width = null, $heigth = null, array $options = [])
    {
        $heigth = empty($heigth) ? $width : $heigth;
        $width = empty($width) ? $heigth : $width;

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
     * @param int $width Required width
     * @param int $heigth Required heigth
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#fit
     * @uses $arguments
     * @uses $callbacks
     */
    public function fit($width = null, $heigth = null, array $options = [])
    {
        $heigth = empty($heigth) ? $width : $heigth;
        $width = empty($width) ? $heigth : $width;

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
     * @param int $width Required width
     * @param int $heigth Required heigth
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
     * @param int $width Required width
     * @param int $heigth Required heigth
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
     * @throws InternalErrorException
     * @uses getDefaultSaveOptions()
     * @uses getImageInstance()
     * @uses $arguments
     * @uses $callbacks
     * @uses $path
     */
    public function save(array $options = [])
    {
        if (empty($this->callbacks)) {
            throw new InternalErrorException(__d('thumber', 'No valid method called before the `{0}` method', __FUNCTION__));
        }

        $options = $this->getDefaultSaveOptions($options);
        $target = $options['target'];

        if (!$target) {
            $this->arguments[] = [$this->getDriver(), $options['format'], $options['quality']];

            $target = sprintf('%s_%s.%s', md5($this->path), md5(serialize($this->arguments)), $options['format']);
        } else {
            $options['format'] = $this->getExtension($target);
        }

        if (!Folder::isAbsolute($target)) {
            $target = $this->getPath($target);
        }

        //Creates the thumbnail, if this does not exist
        if (!file_exists($target)) {
            $imageInstance = $this->getImageInstance();

            //Calls each callback
            foreach ($this->callbacks as $callback) {
                call_user_func($callback, $imageInstance);
            }

            $content = $imageInstance->encode($options['format'], $options['quality']);
            $imageInstance->destroy();

            if (!is_writable(dirname($target))) {
                throw new InternalErrorException(__d('thumber', 'The directory `{0}` is not writeable', rtr(dirname($target))));
            }

            //Writes
            file_put_contents($target, $content);
        }

        //Resets arguments and callbacks
        $this->arguments = $this->callbacks = [];

        return $target;
    }
}
