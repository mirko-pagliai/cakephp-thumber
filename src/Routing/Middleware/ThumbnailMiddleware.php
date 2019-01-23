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
 * @since       1.6.0
 */
namespace Thumber\Routing\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Thumber\Http\Exception\ThumbNotFoundException;
use Thumber\ThumbsPathTrait;

/**
 * Handles serving thumbnails
 */
class ThumbnailMiddleware
{
    use ThumbsPathTrait;

    /**
     * Serves thumbnail if the request matches one
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param callable $next Callback to invoke the next middleware
     * @return ResponseInterface A response
     * @throws ThumbNotFoundException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $file = $this->getPath(base64_decode($request->getParam('basename')));
        is_readable_or_fail($file, __d('thumber', 'File `{0}` doesn\'t exist', $file), ThumbNotFoundException::class);

        $response = $response->withModified(filemtime($file));
        if ($response->checkNotModified($request)) {
            return $response;
        }

        return $response->withFile($file)->withType(mime_content_type($file));
    }
}
