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
     * Internal method to delete thumbnails
     * @param array $filenames Filenames
     * @return bool
     */
    protected static function delete($filenames)
    {
        $success = true;

        foreach ($filenames as $filename) {
            if (!(new File(self::getPath($filename)))->delete()) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Internal method to find thumbnails
     * @param string|null $regexpPattern `preg_match()` pattern
     * @param bool $sort Whether results should be sorted
     * @return array
     */
    protected static function find($regexpPattern = null, $sort = false)
    {
        if (!$regexpPattern) {
            $regexpPattern = sprintf('[a-z0-9]{32}_[a-z0-9]{32}\.(%s)', implode('|', self::getSupportedFormats()));
        }

        return (new Folder(self::getPath()))->find($regexpPattern, $sort);
    }

    /**
     * Deletes all thumbnails
     * @return bool
     * @uses delete()
     * @uses getAll()
     */
    public static function deleteAll()
    {
        return self::delete(self::getAll());
    }

    /**
     * Deletes all thumbnails from a path of an original image
     * @param string $path Path of the original image
     * @return bool
     * @uses delete()
     * @uses getFromPath()
     */
    public static function deleteFromPath($path)
    {
        return self::delete(self::getFromPath($path));
    }

    /**
     * Gets all thumbnails
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses find()
     */
    public static function getAll($sort = false)
    {
        return self::find(null, $sort);
    }

    /**
     * Gets all thumbnails from a path of an original image
     * @param string $path Path of the original image
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses find()
     */
    public static function getFromPath($path, $sort = false)
    {
        $regexpPattern = sprintf('%s_[a-z0-9]{32}\.(%s)', md5(self::resolveFilePath($path)), implode('|', self::getSupportedFormats()));

        return self::find($regexpPattern, $sort);
    }
}
