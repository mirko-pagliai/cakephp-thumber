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

use Cake\Core\Configure;
use Cake\Core\Plugin;
use LogicException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tools\Filesystem;
use function Cake\Core\pluginSplit;

/**
 * A utility to manage thumbnails
 */
class ThumbManager
{
    /**
     * Supported formats
     * @var string[]
     */
    protected const SUPPORTED_FORMATS = ['bmp', 'gif', 'ico', 'jpg', 'png', 'psd', 'tiff'];

    /**
     * Internal method to clear thumbnails
     * @param string[] $filenames Filenames
     * @return int Number of thumbnails deleted
     * @throws \LogicException
     */
    protected function _clear(array $filenames): int
    {
        array_walk($filenames, function (string $filename): void {
            $filename = Filesystem::instance()->concatenate(Configure::readOrFail('Thumber.target'), $filename);
            if (!is_writable($filename)) {
                throw new LogicException('File or directory `' . $filename . '` is not writable'); // @codeCoverageIgnore
            }
            Filesystem::instance()->remove($filename);
        });

        return count($filenames);
    }

    /**
     * Internal method to find thumbnails
     * @param string $pattern A pattern (a regexp, a glob, or a string)
     * @param bool $sort Whether results should be sorted
     * @return string[] Filenames
     */
    protected function _find(string $pattern = '', bool $sort = false): array
    {
        $pattern = $pattern ?: sprintf('/[\d\w]{32}_[\d\w]{32}\.(%s)$/', implode('|', self::SUPPORTED_FORMATS));
        $finder = (new Finder())->files()->name($pattern)->in(Configure::readOrFail('Thumber.target'));

        return array_map(fn(SplFileInfo $file): string => $file->getFilename(), iterator_to_array($sort ? $finder->sortByName() : $finder));
    }

    /**
     * Clears all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @return int Number of thumbnails deleted
     * @throws \LogicException
     */
    public function clear(string $path): int
    {
        return $this->_clear($this->get($path));
    }

    /**
     * Clears all thumbnails
     * @return int Number of thumbnails deleted
     * @throws \LogicException
     */
    public function clearAll(): int
    {
        return $this->_clear($this->getAll());
    }

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
     */
    public function get(string $path, bool $sort = false): array
    {
        $fullPath = $this->resolveFilePath($path);

        if (!is_readable($fullPath)) {
            throw new LogicException('File or directory `' . $path . '` is not readable');
        }
        $pattern = sprintf('/%s_[\d\w]{32}\.(%s)$/', md5($fullPath), implode('|', self::SUPPORTED_FORMATS));

        return $this->_find($pattern, $sort);
    }

    /**
     * Gets all thumbnails
     * @param bool $sort Whether results should be sorted
     * @return string[]
     */
    public function getAll(bool $sort = false): array
    {
        return $this->_find('', $sort);
    }
}
