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

use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Thumber\ThumbTrait;

/**
 * A utility to manage thumbnails
 */
class ThumbManager
{
    use ThumbTrait;

    /**
     * Internal method to clear thumbnails
     * @param array $filenames Filenames
     * @return int|bool Number of thumbnails deleted otherwise `false` in case of error
     */
    protected static function _clear($filenames)
    {
        $count = 0;

        foreach ($filenames as $filename) {
            if (!(new File(self::getPath($filename)))->delete()) {
                return false;
            }

            $count++;
        }

        return $count;
    }

    /**
     * Internal method to find thumbnails
     * @param string|null $regexpPattern `preg_match()` pattern
     * @param bool $sort Whether results should be sorted
     * @return array
     */
    protected static function _find($regexpPattern = null, $sort = false)
    {
        if (!$regexpPattern) {
            $regexpPattern = sprintf('[a-z0-9]{32}_[a-z0-9]{32}\.(%s)', implode('|', self::getSupportedFormats()));
        }

        return (new Folder(self::getPath()))->find($regexpPattern, $sort);
    }

    /**
     * Clears all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @return int|bool Number of thumbnails deleted otherwise `false` in case of error
     * @uses _clear()
     * @uses get()
     */
    public static function clear($path)
    {
        return self::_clear(self::get($path));
    }

    /**
     * Clears all thumbnails
     * @return int|bool Number of thumbnails deleted otherwise `false` in case of error
     * @uses _clear()
     * @uses getAll()
     */
    public static function clearAll()
    {
        return self::_clear(self::getAll());
    }

    /**
     * Gets all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses _find()
     */
    public static function get($path, $sort = false)
    {
        $regexpPattern = sprintf('%s_[a-z0-9]{32}\.(%s)', md5(self::resolveFilePath($path)), implode('|', self::getSupportedFormats()));

        return self::_find($regexpPattern, $sort);
    }

    /**
     * Gets all thumbnails
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses _find()
     */
    public static function getAll($sort = false)
    {
        return self::_find(null, $sort);
    }
}
