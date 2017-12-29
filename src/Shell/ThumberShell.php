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
 * @since       1.3.0
 */
namespace Thumber\Shell;

use Cake\Console\Shell;
use Thumber\Utility\ThumbManager;

/**
 * A shell to manage thumbnails
 */
class ThumberShell extends Shell
{
    /**
     * Internal method to clears thumbnails
     * @param string $method Method to call, provided by the `ThumbManager` class
     * @param array $args Arguments
     * @return bool
     * @uses Thumber\Utility\ThumbManager::clear()
     * @uses Thumber\Utility\ThumbManager::clearAll()
     */
    protected function _clear($method, $args = [])
    {
        $count = call_user_func_array([new ThumbManager, $method], (array)$args);

        if ($count === false) {
            $this->err(__d('thumber', 'Error deleting thumbnails'));

            return false;
        }

        $this->verbose(__d('thumber', 'Thumbnails deleted: {0}', $count));

        return true;
    }

    /**
     * Clears all thumbnails that have been generated from an image path
     * @param string $path Path of the original image
     * @return bool
     * @uses _clear()
     */
    public function clear($path = null)
    {
        return $this->_clear(__FUNCTION__, $path);
    }

    /**
     * Clears all thumbnails
     * @return bool
     * @uses _clear()
     */
    public function clearAll()
    {
        return $this->_clear(__FUNCTION__);
    }

    /**
     * Gets the option parser instance and configures it
     * @return ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('clear', [
            'help' => __d('thumber', 'Clears all thumbnails that have been generated from an image path'),
            'parser' => [
                'arguments' => [
                    'path' => [
                        'help' => __d('thumber', 'Path of the original image'),
                        'required' => true,
                    ],
                ],
            ],
        ]);
        $parser->addSubcommand('clearAll', ['help' => __d('thumber', 'Clears all thumbnails')]);
        $parser->setDescription(__d('thumber', 'A shell to manage thumbnails'));

        return $parser;
    }
}
