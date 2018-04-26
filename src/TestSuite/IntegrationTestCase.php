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
namespace Thumber\TestSuite;

use Cake\TestSuite\IntegrationTestCase as CakeIntegrationTestCase;
use Thumber\ThumbsPathTrait;

/**
 * IntegrationTestCaseTest class
 */
abstract class IntegrationTestCase extends CakeIntegrationTestCase
{
    use ThumbsPathTrait;

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        foreach (glob($this->getPath() . DS . '*') as $file) {
            safe_unlink($file);
        }
    }

    /**
     * Asserts content type
     * @param string $type The content-type to check for
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     */
    public function assertContentType($type, $message = '')
    {
        $this->skipIf(!version_compare(PHP_VERSION, '7.0', '>') &&
            in_array($type, ['image/x-ms-bmp', 'image/vnd.adobe.photoshop']));

        parent::assertContentType($type, $message);
    }
}
