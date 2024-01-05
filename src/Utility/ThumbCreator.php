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
 * @see         https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility
 * @see         https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#save
 */
namespace Thumber\Cake\Utility;

use Cake\Routing\Router;
use LogicException;
use Thumber\ThumbCreator as BaseThumbCreator;
use function Cake\I18n\__d;

/**
 * Utility to create a thumb.
 *
 * Please, refer to the `README` file to know how to use the utility and to see examples.
 */
class ThumbCreator extends BaseThumbCreator
{
    /**
     * Construct.
     *
     * It sets the file path and extension.
     * @param string $path Path of the image from which to create the thumbnail. It can be a relative path from
     *  `APP/webroot/img/`, a full path or a remote url
     */
    public function __construct(string $path)
    {
        parent::__construct(ThumbManager::resolveFilePath($path));
    }

    /**
     * Builds and returns the url for the generated thumbnail
     * @param bool $fullBase If `true`, the full base URL will be prepended to the result
     * @return string
     * @throws \LogicException
     * @since 1.5.1
     */
    public function getUrl(bool $fullBase = true): string
    {
        if (empty($this->target)) {
            throw new LogicException(__d(
                'thumber',
                'Missing path of the generated thumbnail. Probably the `{0}` method has not been invoked',
                'save()'
            ));
        }

        return Router::url(['_name' => 'thumb', base64_encode(basename($this->target))], $fullBase);
    }
}
