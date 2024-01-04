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
namespace Thumber\Cake\Middleware;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Thumber\Cake\Http\Exception\ThumbNotFoundException;
use Tools\Filesystem;

/**
 * Handles serving thumbnails
 */
class ThumbnailMiddleware implements MiddlewareInterface
{
    /**
     * Serves thumbnail if the request matches one
     * @param \Psr\Http\Message\ServerRequestInterface $request The request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler Request handler
     * @return \Psr\Http\Message\ResponseInterface A response
     * @throws \Thumber\Cake\Http\Exception\ThumbNotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        $file = Filesystem::instance()->concatenate(THUMBER_TARGET, base64_decode($request->getParam('basename')));
        if (!is_readable($file)) {
            throw new ThumbNotFoundException(__d('thumber', "File `{0}` doesn't exist", $file));
        }

        $Response = new Response();
        $Response = $Response->withModified(filemtime($file) ?: 0);
        if ($Response->isNotModified($request)) {
            return $Response->withNotModified();
        }

        return $Response->withFile($file)->withType(mime_content_type($file) ?: '');
    }
}
