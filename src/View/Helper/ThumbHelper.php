<?php
declare(strict_types=1);

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
 * @see         https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-use-the-helper
 */
namespace Thumber\Cake\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;
use LogicException;
use Thumber\Cake\Utility\ThumbCreator;

/**
 * Thumb Helper.
 *
 * This helper allows you to generate thumbnails.
 * @method string crop($path, array $params = [], array $options = []) Crops the image, cutting out a rectangular part of the image
 * @method string cropUrl($path, array $params = [], array $options = []) As for the `crop()` method, but this only returns the url
 * @method string fit($path, array $params = [], array $options = []) Resizes the image, combining cropping and resizing to format image in a smart way. It will find the best fitting aspect ratio on the current image automatically, cut it out and resize it to the given dimension
 * @method string fitUrl($path, array $params = [], array $options = []) As for the `fit()` method, but this only returns the url
 * @method string resize($path, array $params = [], array $options = []) Resizes the image
 * @method string resizeUrl($path, array $params = [], array $options = []) As for the `resize()` method, but this only returns the url
 * @method string resizeCanvas($path, array $params = [], array $options = []) Resizes the boundaries of the current image to given width and height. An anchor can be defined to determine from what point of the image the resizing is going to happen. Set the mode to relative to add or subtract the given width or height to the actual image dimensions. You can also pass a background color for the emerging area of the image
 * @method string resizeCanvasUrl($path, array $params = [], array $options = []) As for the `resizeCanvas()` method, but this only returns the url
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class ThumbHelper extends Helper
{
    /**
     * Helpers
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Magic method. It dynamically calls all other methods.
     *
     * Each method takes these arguments:
     *  - $path Image path from which to create the thumbnail as relative path (to `APP/webroot/img`), full path or remote url;
     *  - $params Parameters for creating the thumbnail;
     *  - $options Array of HTML attributes for the `img` element.
     * @param string $method Method to invoke
     * @param array $params Array of params for the method
     * @return string
     * @throws \LogicException
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-use-the-helper
     * @since 1.4.0
     */
    public function __call(string $method, array $params): string
    {
        [$path, $params, $options] = $params + [null, [], []];
        if (empty($path)) {
            throw new LogicException(__d('thumber', 'Thumbnail path is missing'));
        }
        $url = $this->runUrlMethod($method, $path, $params, $options);

        return $this->isUrlMethod($method) ? $url : $this->Html->image($url, $options);
    }

    /**
     * Checks is a method name is an "Url" method.
     *
     * This means that the last characters of the method name are "Url".
     *
     * Example: `cropUrl` is an "Url" method. `crop` is not.
     * @param string $name Method name
     * @return bool
     * @since 1.4.0
     */
    protected function isUrlMethod(string $name): bool
    {
        return str_ends_with($name, 'Url');
    }

    /**
     * Runs an "Url" method and returns the url generated by the method
     * @param string $name Method name
     * @param string $path Image path from which to create the thumbnail as relative path (to `APP/webroot/img`), full path or remote url
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string Thumbnail url
     * @throws \BadMethodCallException
     * @since 1.4.0
     */
    protected function runUrlMethod(string $name, string $path, array $params = [], array $options = []): string
    {
        $name = $this->isUrlMethod($name) ? substr($name, 0, -3) : $name;
        $params += ['format' => 'jpg', 'height' => 0, 'width' => 0];
        $options += ['fullBase' => true];

        $className = ThumbCreator::class;
        if (!method_exists($className, $name)) {
            throw new BadMethodCallException(__d('thumber', 'Method `{0}::{1}()` does not exist', $className, $name));
        }
        $Thumber = new $className($path);
        $Thumber->$name($params['width'], $params['height'])->save($params);

        return $Thumber->getUrl($options['fullBase']);
    }
}
