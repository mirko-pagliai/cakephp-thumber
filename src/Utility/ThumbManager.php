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

use Cake\Filesystem\Folder;
use Thumber\ThumbsPathTrait;

/**
 * A utility to manage thumbnails
 */
class ThumbManager
{
    use ThumbsPathTrait;

    /**
     * Supported formats
     * @var array
     */
    public static $supportedFormats = ['bmp', 'gif', 'ico', 'jpg', 'png', 'psd', 'tiff'];

    /**
     * Internal method to clear thumbnails
     * @param array $filenames Filenames
     * @return int|bool Number of thumbnails deleted otherwise `false` in case of error
     */
    protected function _clear($filenames)
    {
        $count = 0;

        foreach ($filenames as $filename) {
            if (!@unlink($this->getPath($filename))) {
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
    protected function _find($regexpPattern = null, $sort = false)
    {
        $regexpPattern = $regexpPattern ?: sprintf('[\d\w]{32}_[\d\w]{32}\.(%s)', implode('|', self::$supportedFormats));

        return (new Folder($this->getPath()))->find($regexpPattern, $sort);
    }

    /**
     * Clears all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @return int|bool Number of thumbnails deleted otherwise `false` in case of error
     * @uses _clear()
     * @uses get()
     */
    public function clear($path)
    {
        return $this->_clear($this->get($path));
    }

    /**
     * Clears all thumbnails
     * @return int|bool Number of thumbnails deleted otherwise `false` in case of error
     * @uses _clear()
     * @uses getAll()
     */
    public function clearAll()
    {
        return $this->_clear($this->getAll());
    }

    /**
     * Gets all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses _find()
     */
    public function get($path, $sort = false)
    {
        $regexpPattern = sprintf('%s_[\d\w]{32}\.(%s)', md5($this->resolveFilePath($path)), implode('|', self::$supportedFormats));

        return $this->_find($regexpPattern, $sort);
    }

    /**
     * Gets all thumbnails
     * @param bool $sort Whether results should be sorted
     * @return array
     * @uses _find()
     */
    public function getAll($sort = false)
    {
        return $this->_find(null, $sort);
    }
}
