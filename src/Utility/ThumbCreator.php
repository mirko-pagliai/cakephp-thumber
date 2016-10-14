<?php
/**
 * This file is part of cakephp-thumber.
 *
 * cakephp-thumber is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-thumber is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-thumber.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace Thumber\Utility;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Network\Exception\InternalErrorException;
use Intervention\Image\ImageManager;

/**
 * Utility to create a thumb.
 *
 * Please, refer to the `README` file to know how to use the utility and to
 * see examples.
 */
class ThumbCreator
{
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
     * File extension
     * @var string
     */
    protected $extension;

    /**
     * File path
     * @var string
     */
    protected $path;

    /**
     * Construct.
     * It sets the file path and extension.
     *
     * If the path is relative, it will be relative to  `APP/webroot/img`.
     * @param string $path File path
     * @return \Thumber\Utility\ThumbCreator
     * @uses _getExtension()
     * @uses _resolveFilePath()
     * @uses $arguments
     * @uses $extension
     * @uses $path
     */
    public function __construct($path)
    {
        $this->path = $this->_resolveFilePath($path);
        $this->extension = $this->_getExtension($this->path);
        $this->arguments[] = $this->path;

        return $this;
    }

    /**
     * Gets the extension for a file
     * @param string $path File path
     * @return string
     */
    protected function _getExtension($path)
    {
        $extension = strtolower(pathinfo(explode('?', $this->path, 2)[0], PATHINFO_EXTENSION));

        if ($extension === 'jpeg') {
            return 'jpg';
        }

        return $extension;
    }

    /**
     * Internal method to resolve a partial path, returning its full path
     * @param string $path Partial path
     * @return string
     * @throws InternalErrorException
     */
    protected function _resolveFilePath($path)
    {
        //Returns, if it's a remote file
        if (isUrl($path)) {
            return $path;
        }

        //If it a relative path, it can be a file from a plugin or a file
        //  relative to `APP/webroot/img/`
        if (!Folder::isAbsolute($path)) {
            $pluginSplit = pluginSplit($path);

            //Note that using `pluginSplit()` is not sufficient, because
            //  `$path` may still contain a dot
            if (!empty($pluginSplit[0]) && in_array($pluginSplit[0], Plugin::loaded())) {
                $path = Plugin::path($pluginSplit[0]) . 'webroot' . DS . 'img' . DS . $pluginSplit[1];
            } else {
                $path = WWW_ROOT . 'img' . DS . $path;
            }
        }

        //Checks if is readable
        if (!is_readable($path)) {
            throw new InternalErrorException(
                __d('thumber', 'File `{0}` not readable', str_replace(APP, null, $path))
            );
        }

        return $path;
    }

    /**
     * Crops the image (cuts out a rectangular part).
     *
     * You can use `x` and `y` options to move the top-left corner of the
     * cutout to a certain position.
     * @param int $width Width of the thumbnail
     * @param int $heigth Height of the thumbnail
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @throws InternalErrorException
     * @uses $arguments
     * @uses $callbacks
     */
    public function crop($width = null, $heigth = null, array $options = [])
    {
        if (empty($width) && empty($heigth)) {
            throw new InternalErrorException(
                __d('thumber', 'Missing parameters for the `{0}` method', __FUNCTION__)
            );
        } else {
            $heigth = empty($heigth) ? $width : $heigth;
            $width = empty($width) ? $heigth : $width;
        }

        //Sets default options
        $options += ['x' => null, 'y' => null];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function ($imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->crop($width, $heigth, $options['x'], $options['y']);
        };

        return $this;
    }

    /**
     * Combines cropping and resizing to format image in a smart way. It will
     *  find the best fitting aspect ratio on the current image automatically,
     *  cut it out and resize it to the given dimension.
     *
     * You can use `position` and `upsize` options.
     * @param int $width Width of the thumbnail
     * @param int $heigth Height of the thumbnail
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @throws InternalErrorException
     * @uses $arguments
     * @uses $callbacks
     */
    public function fit($width = null, $heigth = null, array $options = [])
    {
        if (empty($width) && empty($heigth)) {
            throw new InternalErrorException(
                __d('thumber', 'Missing parameters for the `{0}` method', __FUNCTION__)
            );
        } else {
            $heigth = empty($heigth) ? $width : $heigth;
            $width = empty($width) ? $heigth : $width;
        }

        //Sets default options
        $options += ['position' => 'center', 'upsize' => true];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function ($imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->fit($width, $heigth, function ($constraint) use ($options) {
                if ($options['upsize']) {
                    $constraint->upsize();
                }
            }, $options['position']);
        };

        return $this;
    }

    /**
     * Resizes the image.
     *
     * You can use `aspectRatio` and `upsize` options.
     * @param int $width Width of the thumbnail
     * @param int $heigth Height of the thumbnail
     * @param array $options Options for the thumbnail
     * @return \Thumber\Utility\ThumbCreator
     * @throws InternalErrorException
     * @uses $arguments
     * @uses $callbacks
     */
    public function resize($width = null, $heigth = null, array $options = [])
    {
        if (empty($width) && empty($heigth)) {
            throw new InternalErrorException(
                __d('thumber', 'Missing parameters for the `{0}` method', __FUNCTION__)
            );
        }

        //Sets default options
        $options += ['aspectRatio' => true, 'upsize' => true];

        //Adds arguments
        $this->arguments[] = [__FUNCTION__, $width, $heigth, $options];

        //Adds the callback
        $this->callbacks[] = function ($imageInstance) use ($width, $heigth, $options) {
            return $imageInstance->resize($width, $heigth, function ($constraint) use ($options) {
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
     * Saves the thumbnail
     * @param string|null $target Full path where to save the thumbnail
     * @return string Thumbnail path
     * @uses $arguments
     * @uses $callbacks
     * @uses $extension
     * @uses $path
     */
    public function save($target = null)
    {
        if (empty($this->callbacks)) {
            throw new InternalErrorException(
                __d('thumber', 'No valid method called before the `{0}` method', 'save()')
            );
        }

        if (empty($target)) {
            $target = Configure::read('Thumbs.target') . DS . md5(serialize($this->arguments)) . '.' . $this->extension;
        }

        //Creates the thumbnail, if this does not exist
        if (!file_exists($target)) {
            $imageInstance = (new ImageManager([
                'driver' => Configure::read('Thumbs.driver'),
            ]))->make($this->path);

            //Calls each callback
            foreach ($this->callbacks as $callback) {
                call_user_func($callback, $imageInstance);
            }

            //@codingStandardsIgnoreLine
            $write = @file_put_contents($target, $imageInstance->encode());

            $imageInstance->destroy();

            if (!$write) {
                throw new InternalErrorException(
                    __d('thumber', 'Can\'t write the file `{0}`', str_replace(APP, null, $target))
                );
            }
        }

        //Resets arguments and callbacks
        $this->arguments = $this->callbacks = [];

        return $target;
    }
}
