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
namespace Thumber\View\Helper;

use Cake\View\Helper;
use Thumber\Utility\ThumbCreator;

/**
 * Thumb Helper.
 *
 * This helper allows you to generate thumbnails.
 */
class ThumbHelper extends Helper
{
    /**
     * Helpers
     * @var array
     */
    public $helpers = [
        'Thumber.Html' => ['className' => 'Thumber.Html'],
        'Url',
    ];

    /**
     * Internal method to get the url for a thumbnail
     * @param string $path Thumbnail path
     * @param bool $full If `true`, the full base URL will be prepended to the result
     * @return string
     */
    protected function _getUrl($path, $full = false)
    {
        return $this->Url->build(['_name' => 'thumb', base64_encode(basename($path))], $full);
    }

    /**
     * Internal method to parse parameters
     * @param array $params Parameters for creating the thumbnail
     * @return array Array with width and height
     */
    protected function _parseParams($params)
    {
        return [
            empty($params['width']) ? null : $params['width'],
            empty($params['height']) ? null : $params['height'],
        ];
    }

    /**
     * Creates a cropped thumbnail and returns a formatted `img` element.
     *
     * You can use `width`, `height` and `format` parameters.
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses cropUrl()
     */
    public function crop($path, array $params = [], array $options = [])
    {
        return $this->Html->image($this->cropUrl($path, $params, $options), $options);
    }

    /**
     * Creates a cropped thumbnail and returns its url.
     *
     * You can use `width`, `height` and `format` parameters.
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses _getUrl()
     * @uses _parseParams()
     */
    public function cropUrl($path, array $params = [], array $options = [])
    {
        list($width, $height) = $this->_parseParams($params);

        $params += ['format' => 'jpg'];

        //Creates the thumbnail
        $thumb = (new ThumbCreator($path))->crop($width, $height)->save($params);

        $full = !empty($options['fullBase']) && $options['fullBase'] == true;

        return $this->_getUrl($thumb, $full);
    }

    /**
     * Creates a thumbnail, combining cropping and resizing to format image in
     *   a smart way, and returns a formatted `img` element.
     *
     * You can use `width`, `height` and `format` parameters.
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses fitUrl()
     */
    public function fit($path, array $params = [], array $options = [])
    {
        return $this->Html->image($this->fitUrl($path, $params, $options), $options);
    }

    /**
     * Creates a thumbnail, combining cropping and resizing to format image in
     *   a smart way, and returns its url.
     *
     * You can use `width`, `height` and `format` parameters.
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses _getUrl()
     * @uses _parseParams()
     */
    public function fitUrl($path, array $params = [], array $options = [])
    {
        list($width, $height) = $this->_parseParams($params);

        $params += ['format' => 'jpg'];

        //Creates the thumbnail
        $thumb = (new ThumbCreator($path))->fit($width, $height)->save($params);

        $full = !empty($options['fullBase']) && $options['fullBase'] == true;

        return $this->_getUrl($thumb, $full);
    }

    /**
     * Creates a resized thumbnail and returns a formatted `img` element.
     *
     * You can use `width`, `height` and `format` parameters.
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses resizeUrl()
     */
    public function resize($path, array $params = [], array $options = [])
    {
        return $this->Html->image($this->resizeUrl($path, $params, $options), $options);
    }

    /**
     * Creates a resizes thumbnail and returns its url.
     *
     * You can use `width`, `height` and `format` parameters.
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses _getUrl()
     * @uses _parseParams()
     */
    public function resizeUrl($path, array $params = [], array $options = [])
    {
        list($width, $height) = $this->_parseParams($params);

        $params += ['format' => 'jpg'];

        //Creates the thumbnail
        $thumb = (new ThumbCreator($path))->resize($width, $height)->save($params);

        $full = !empty($options['fullBase']) && $options['fullBase'] == true;

        return $this->_getUrl($thumb, $full);
    }
}
