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
namespace Thumber\Cake\Controller;

use Cake\Controller\Controller;
use Thumber\Cake\Http\Exception\ThumbNotFoundException;
use Tools\Exceptionist;
use Tools\Filesystem;

/**
 * Thumbs controller class
 */
class ThumbsController extends Controller
{
    /**
     * Renders a thumbnail
     * @param string $basename Encoded thumbnail basename
     * @return \Cake\Network\Response|null
     * @throws \Thumber\Cake\Http\Exception\ThumbNotFoundException
     */
    public function thumb($basename)
    {
        $file = (new Filesystem())->concatenate(THUMBER_TARGET, base64_decode($basename));
        Exceptionist::isReadable($file, __d('thumber', 'File `{0}` doesn\'t exist', $file), ThumbNotFoundException::class);

        if (!is_readable($file)) {
            throw new ThumbNotFoundException(__d('thumber', 'File `{0}` doesn\'t exist', $file));
        }

        $this->response->modified((int)filemtime($file));

        if ($this->response->checkNotModified($this->request)) {
            return $this->response;
        }

        $this->response->file($file);
        $this->response->type(mime_content_type($file) ?: '');

        return $this->response;
    }
}
