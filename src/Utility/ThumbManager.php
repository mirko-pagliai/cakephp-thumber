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
 * @since       1.3.0
 */
namespace Thumber\Cake\Utility;

use Cake\Core\Plugin;
use Thumber\ThumbManager as BaseThumbManager;
use Tools\Filesystem;

/**
 * A utility to manage thumbnails
 */
class ThumbManager extends BaseThumbManager
{
    /**
     * Internal method to resolve a relative path, returning a full path
     * @param string $path Partial path
     * @return string
     */
    public static function resolveFilePath(string $path): string
    {
        $Filesystem = new Filesystem();

        //A relative path can be a file from `APP/webroot/img/` or a plugin
        if (!is_url($path) && !$Filesystem->isAbsolutePath($path)) {
            $pluginSplit = pluginSplit($path);
            if ($pluginSplit[0] && in_array($pluginSplit[0], Plugin::loaded())) {
                $www = $Filesystem->concatenate(Plugin::path($pluginSplit[0]), 'webroot');
                $path = $pluginSplit[1];
            }
            $path = $Filesystem->concatenate($www ?? WWW_ROOT, 'img', $path);
        }

        return $path;
    }

    /**
     * Gets all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @param bool $sort Whether results should be sorted
     * @return array<string, string>
     * @throws \Tools\Exception\NotReadableException
     */
    public function get(string $path, bool $sort = false): array
    {
        return parent::get($this->resolveFilePath($path), $sort);
    }
}
