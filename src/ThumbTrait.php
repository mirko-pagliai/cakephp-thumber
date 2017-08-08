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
use Cake\Routing\Router;

/**
 * This trait provides several methods used by other classes
 */
trait ThumbTrait
{
    /**
     * Gets a path for a thumbnail
     * @param string|null $file File
     * @return string
     */
    protected function getPath($file = null)
    {
        $path = Configure::read(THUMBER . '.target');

        if ($file) {
            $path .= DS . $file;
        }

        return $path;
    }

    /**
     * Gets the url for a thumbnail
     * @param string $path Thumbnail path
     * @param bool $full If `true`, the full base URL will be prepended to the
     *  result
     * @return string
     */
    protected function getUrl($path, $full = true)
    {
        return Router::url(['_name' => 'thumb', base64_encode(basename($path))], $full);
    }
}
