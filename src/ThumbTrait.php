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
 * @since       1.1.1
 */
namespace Thumber;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Router;

/**
 * This trait provides several methods used by other classes
 */
trait ThumbTrait
{
    /**
     * Gets the current driver
     * @return string
     */
    protected function getDriver()
    {
        return Configure::read(THUMBER . '.driver');
    }

    /**
     * Gets the extension for a file
     * @param string $path File path
     * @return string
     */
    protected function getExtension($path)
    {
        $extension = get_extension($path);

        switch ($extension) {
            case 'jpeg':
                return 'jpg';
            case 'tif':
                return 'tiff';
            default:
                return $extension;
        }
    }

    /**
     * Gets a path for a thumbnail
     * @param string $file File
     * @return string
     */
    protected function getPath($file = null)
    {
        $path = Configure::readOrFail(THUMBER . '.target');

        return $file ? $path . DS . $file : $path;
    }

    /**
     * Returns the supported formats
     * @return array Supported formats
     */
    protected function getSupportedFormats()
    {
        return ['bmp', 'gif', 'ico', 'jpg', 'png', 'psd', 'tiff'];
    }

    /**
     * Internal method to resolve a partial path, returning its full path
     * @param string $path Partial path
     * @return string
     * @throws InternalErrorException
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
            if (!empty($pluginSplit[0]) && in_array($pluginSplit[0], Plugin::loaded())) {
                $path = Plugin::path($pluginSplit[0]) . 'webroot' . DS . 'img' . DS . $pluginSplit[1];
            }
        }

        //Checks if is readable
        if (!is_readable($path)) {
            throw new InternalErrorException(__d('thumber', 'File `{0}` not readable', rtr($path)));
        }

        return $path;
    }
}
