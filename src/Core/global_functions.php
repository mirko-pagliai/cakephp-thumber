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
 */
use Cake\Filesystem\Folder;

if (!function_exists('isUrl')) {
    /**
     * Checks whether a url is valid
     * @param string $url Url
     * @return bool
     */
    function isUrl($url)
    {
        return (bool)preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url);
    }
}

if (!function_exists('rtr')) {
    /**
     * Returns the relative path (to the APP root) of an absolute path
     * @param string $path Absolute path
     * @return string Relativa path
     */
    function rtr($path)
    {
        return preg_replace(sprintf('/^%s/', preg_quote(Folder::slashTerm(ROOT), DS)), null, $path);
    }
}
