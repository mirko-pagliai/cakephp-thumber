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

use Cake\Routing\Router;
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
    public $helpers = ['Html'];

    /**
     * Creates a cropped thumbnail and returns a formatted `img` element
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses cropUrl()
     */
    public function crop($path, array $params = [], array $options = [])
    {
        return $this->Html->image($this->cropUrl($path, $params), $options);
    }

    /**
     * Creates a cropped thumbnail and returns its url
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @return string
     */
    public function cropUrl($path, array $params = [])
    {
        $width = empty($params['width']) ? null : $params['width'];
        $height = empty($params['height']) ? null : $params['height'];

        //Creates the thumbnail
        $thumb = (new ThumbCreator($path))->crop($width, $height)->save();

        return Router::url(['_name' => 'thumb', base64_encode(basename($thumb))]);
    }

    /**
     * Creates a resized thumbnail and returns a formatted `img` element
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @param array $options Array of HTML attributes for the `img` element
     * @return string
     * @uses resizeUrl()
     */
    public function resize($path, array $params = [], array $options = [])
    {
        return $this->Html->image($this->resizeUrl($path, $params), $options);
    }

    /**
     * Creates a resizes thumbnail and returns its url
     * @param string $path File path
     * @param array $params Parameters for creating the thumbnail
     * @return string
     */
    public function resizeUrl($path, array $params = [])
    {
        $width = empty($params['width']) ? null : $params['width'];
        $height = empty($params['height']) ? null : $params['height'];

        //Creates the thumbnail
        $thumb = (new ThumbCreator($path))->resize($width, $height)->save();

        return Router::url(['_name' => 'thumb', base64_encode(basename($thumb))]);
    }
}
