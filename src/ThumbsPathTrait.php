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
        if (!is_absolute($path)) {
            $pluginSplit = pluginSplit($path);
            $www = add_slash_term(WWW_ROOT);
            if ($pluginSplit[0] && in_array($pluginSplit[0], CorePlugin::loaded())) {
                $www = add_slash_term(CorePlugin::path($pluginSplit[0])) . 'webroot' . DS;
                $path = $pluginSplit[1];
            }
            $path = $www . 'img' . DS . $path;
        }
        is_readable_or_fail($path);

        return $path;
    }
}
