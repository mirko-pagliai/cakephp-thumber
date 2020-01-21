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

namespace Thumber\Test\TestCase;

use Cake\I18n\I18n;
use MeTools\TestSuite\TestCase;

/**
 * I18nTest class
 */
class I18nTest extends TestCase
{
    /**
     * Tests I18n translations
     * @test
     */
    public function testI18nConstant()
    {
        $translator = I18n::translator('thumber', 'it');
        $this->assertEquals('Cancella tutte le miniature', $translator->translate('Clears all thumbnails'));
    }
}
