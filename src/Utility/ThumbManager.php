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
 * @since       1.3.0
 */
namespace Thumber\Utility;

use Cake\Core\Plugin;
use PhpThumber\ThumbManager as PhpThumberThumbManager;

/**
 * A utility to manage thumbnails
 */
class ThumbManager extends PhpThumberThumbManager
{
    /**
     * Internal method to resolve a relative path, returning a full path
     * @param string $path Partial path
     * @return string
     */
    public static function resolveFilePath($path)
    {
        //A relative path can be a file from `APP/webroot/img/` or a plugin
        if (!is_url($path) && !is_absolute($path)) {
            $pluginSplit = pluginSplit($path);
            $www = WWW_ROOT;
            if ($pluginSplit[0] && in_array($pluginSplit[0], Plugin::loaded())) {
                $www = add_slash_term(Plugin::path($pluginSplit[0])) . 'webroot';
                $path = $pluginSplit[1];
            }
            $path = add_slash_term($www) . 'img' . DS . $path;
        }

        return $path;
    }

    /**
     * Gets all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses resolveFilePath()
     */
    public function get($path, $sort = false)
    {
        return parent::get($this->resolveFilePath($path), $sort);
    }
}
