<?php
/**
 * This file is part of cakephp-thumber.
 *
 * cakephp-thumber is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-thumber is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-thumber.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
use Cake\Core\Configure;

//Sets the default Thumber name
if (!defined('THUMBER')) {
    define('THUMBER', 'Thumbs');
}

//Default thumbnails driver
if (!Configure::check(THUMBER . '.driver')) {
    Configure::write(THUMBER . '.driver', 'imagick');
}

//Default thumbnails directory
if (!Configure::check(THUMBER . '.target')) {
    Configure::write(THUMBER . '.target', TMP . 'thumbs');
}

if (!in_array(Configure::read(THUMBER . '.driver'), ['imagick', 'gd'])) {
    trigger_error(
        sprintf('The driver %s is not supported', Configure::read(THUMBER . '.driver')),
        E_USER_ERROR
    );
}

if (!is_writeable(Configure::read(THUMBER . '.target'))) {
    trigger_error(
        sprintf('Directory %s not writeable', Configure::read(THUMBER . '.target')),
        E_USER_ERROR
    );
}
