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
 * @since       1.5.0
 */
namespace Thumber;

use Cake\Core\Configure;
use Cake\Core\Plugin as CorePlugin;
use Cake\Filesystem\Folder;

/**
 * This trait provides some methods to get and resolve thumbnails paths.
 */
trait ThumbsPathTrait
{
    /**
     * Gets a path for a thumbnail.
     *
     * Called with the `$file` argument, returns the file absolute path.
     * Otherwise, called with `null`, returns the path of the target directory.
     * @param string|null $file File
     * @return string
     */
    protected function getPath($file = null)
    {
        $path = Configure::readOrFail('Thumber.target');

        return $file ? $path . DS . $file : $path;
    }

    /**
     * Internal method to resolve a partial path, returning a full path
     * @param string $path Partial path
     * @return string
     */
    protected function resolveFilePath($path)
    {
        //Returns, if it's a remote file
        if (is_url($path)) {
            return $path;
        }

        //If it a relative path, it can be a file from a plugin or a file
        //  relative to `APP/webroot/img/`
        if (!Folder::isAbsolute($path)) {
            $pluginSplit = pluginSplit($path);

            //Note that using `pluginSplit()` is not sufficient, because
            //  `$path` may still contain a dot
            $path = WWW_ROOT . 'img' . DS . $path;
            if (!empty($pluginSplit[0]) && in_array($pluginSplit[0], CorePlugin::loaded())) {
                $path = CorePlugin::path($pluginSplit[0]) . 'webroot' . DS . 'img' . DS . $pluginSplit[1];
            }
        }

        is_readable_or_fail($path);

        return $path;
    }
}
