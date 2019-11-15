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
namespace Thumber\Cake\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Exception;
use MeTools\Console\Command;
use Thumber\Cake\Utility\ThumbManager;

/**
 * Clears all thumbnails
 */
class ClearAllCommand extends Command
{
    /**
     * A `ThumbManager` instance
     * @var \Thumber\Cake\Utility\ThumbManager
     */
    public $ThumbManager;

    /**
     * Hook method invoked by CakePHP when a command is about to be executed
     * @return void
     * @uses $ThumbManager
     */
    public function initialize(): void
    {
        $this->ThumbManager = $this->ThumbManager ?: new ThumbManager();
    }

    /**
     * Hook method for defining this command's option parser
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return $parser->setDescription(__d('thumber', 'Clears all thumbnails'));
    }

    /**
     * Clears all thumbnails
     * @param \Cake\Console\Arguments $args The command arguments
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     * @uses $ThumbManager
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        try {
            $count = $this->ThumbManager->clearAll();
        } catch (Exception $e) {
            $io->err(__d('thumber', 'Error deleting thumbnails'));
            $this->abort();
        }

        $io->verbose(__d('thumber', 'Thumbnails deleted: {0}', $count));

        return null;
    }
}
