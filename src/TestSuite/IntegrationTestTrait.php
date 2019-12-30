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
 * @since       1.7.0
 */
namespace Thumber\Cake\TestSuite;

use Cake\TestSuite\IntegrationTestTrait as CakeIntegrationTestTrait;

/**
 * A trait intended to make integration tests of your controllers easier
 */
trait IntegrationTestTrait
{
    use CakeIntegrationTestTrait {
        CakeIntegrationTestTrait::assertContentType as cakeAssertContentType;
    }

    /**
     * Asserts content type
     * @param string $type The content-type to check for
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     */
    public function assertContentType(string $type, string $message = ''): void
    {
        $this->skipIf(!version_compare(PHP_VERSION, '7.0', '>') &&
            in_array($type, ['image/x-ms-bmp', 'image/vnd.adobe.photoshop']));

        $this->cakeAssertContentType($type, $message);
    }
}
