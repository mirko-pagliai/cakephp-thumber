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
 * @see         https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility
 */
namespace Thumber\Cake\Utility;

use BadMethodCallException;
use Cake\Routing\Router;
use Intervention\Image\Image;
use InvalidArgumentException;
use Thumber\Cake\Utility\ThumbManager;
use Thumber\Exception\NotReadableImageException;
use Thumber\Exception\NotWritableException;
use Thumber\Exception\UnsupportedImageTypeException;
use Thumber\ThumbCreator as BaseThumbCreator;

/**
 * Utility to create a thumb.
 *
 * Please, refer to the `README` file to know how to use the utility and to
 * see examples.
 */
class ThumbCreator extends BaseThumbCreator
{
    /**
     * Construct.
     * It sets the file path and extension.
     * @param string $path Path of the image from which to create the
     *  thumbnail. It can be a relative path from `APP/webroot/img/`, a full
     *  path or a remote url
     * @uses \Thumber\Cake\Utility\ThumbManager::resolveFilePath()
     */
    public function __construct($path)
    {
        parent::__construct(ThumbManager::resolveFilePath($path));
    }

    /**
     * Gets an `Image` instance
     * @return \Intervention\Image\Image
     */
    protected function getImageInstance()
    {
        try {
            return parent::getImageInstance();
        } catch (UnsupportedImageTypeException $e) {
            $message = __d('thumber', 'Image type `{0}` is not supported by this driver', $e->getImageType());
            throw new UnsupportedImageTypeException($message);
        } catch (NotReadableImageException $e) {
            $message = __d('thumber', 'Unable to read image from file `{0}`', $e->getFilePath());
            throw new NotReadableImageException($message);
        }
    }

    /**
     * Builds and returns the url for the generated thumbnail
     * @param bool $fullBase If `true`, the full base URL will be prepended to
     *  the result
     * @return string
     * @since 1.5.1
     * @throws \InvalidArgumentException
     * @uses $target
     */
    public function getUrl($fullBase = true)
    {
        is_true_or_fail($this->target, __d(
            'thumber',
            'Missing path of the generated thumbnail. Probably the `{0}` method has not been invoked',
            'save()'
        ), InvalidArgumentException::class);

        return Router::url(['_name' => 'thumb', base64_encode(basename($this->target))], $fullBase);
    }

    /**
     * Saves the thumbnail and returns its path
     * @param array $options Options for saving
     * @return string Thumbnail path
     * @see https://github.com/mirko-pagliai/cakephp-thumber/wiki/How-to-uses-the-ThumbCreator-utility#save
     */
    public function save(array $options = [])
    {
        try {
            return parent::save($options);
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(__d('thumber', 'No valid method called before the `{0}` method', 'save()'));
        } catch (NotWritableException $e) {
            throw new NotWritableException(__d('thumber', 'Unable to create file `{0}`', $e->getFilePath()));
        }
    }
}
