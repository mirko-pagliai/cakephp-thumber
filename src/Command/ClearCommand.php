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

namespace Thumber\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Exception;
use Thumber\Utility\ThumbManager;
use function Cake\I18n\__d;

/**
 * Clears all thumbnails that have been generated from an image path
 */
class ClearCommand extends Command
{
    /**
     * Hook method for defining this command's option parser
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return $parser->setDescription(__d('thumber', 'Clears all thumbnails that have been generated from an image path'))
            ->addArgument('path', [
                'help' => __d('thumber', 'Path of the original image'),
                'required' => true,
            ]);
    }

    /**
     * Internal method to get a `ThumbManager` instance
     * @return \Thumber\Utility\ThumbManager
     */
    protected function getThumbManager(): ThumbManager
    {
        return new ThumbManager();
    }

    /**
     * Clears all thumbnails that have been generated from an image path
     * @param \Cake\Console\Arguments $args The command arguments
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return void
     */
    public function execute(Arguments $args, ConsoleIo $io): void
    {
        try {
            $count = $this->getThumbManager()->clear((string)$args->getArgument('path'));
        } catch (Exception) {
            $io->err(__d('thumber', 'Error deleting thumbnails'));
            $this->abort();
        }

        $io->verbose(__d('thumber', 'Thumbnails deleted: {0}', $count));
    }
}
