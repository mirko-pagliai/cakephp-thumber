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
 * @since       1.6.0
 */
namespace Thumber\Cake\Routing\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Thumber\Cake\Http\Exception\ThumbNotFoundException;

/**
 * Handles serving thumbnails
 */
class ThumbnailMiddleware
{
    /**
     * Serves thumbnail if the request matches one
     * @param \Psr\Http\Message\ServerRequestInterface $request The request
     * @param \Psr\Http\Message\ResponseInterface $response The response
     * @return \Psr\Http\Message\ResponseInterface A response
     * @throws \Thumber\Http\Exception\ThumbNotFoundException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $file = add_slash_term(THUMBER_TARGET) . base64_decode($request->getParam('basename'));
        is_readable_or_fail($file, __d('thumber', 'File `{0}` doesn\'t exist', $file), ThumbNotFoundException::class);

        $response = $response->withModified(filemtime($file));
        if ($response->checkNotModified($request)) {
            return $response;
        }

        return $response->withFile($file)->withType(mime_content_type($file));
    }
}
