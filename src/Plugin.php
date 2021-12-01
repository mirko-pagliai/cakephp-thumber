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
 * @since       1.5.0
 */
namespace Thumber\Cake;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Thumber\Cake\Command\ClearAllCommand;
use Thumber\Cake\Command\ClearCommand;
use Tools\Filesystem;

/**
 * Plugin class
 */
class Plugin extends BasePlugin
{
    /**
     * Load all the application configuration and bootstrap logic
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        //Loads bootstrap for `php-thumber`
        require_once Filesystem::instance()->concatenate(ROOT, 'vendor', 'mirko-pagliai', 'php-thumber', 'config', 'bootstrap.php');
    }

    /**
     * Add console commands for the plugin
     * @param \Cake\Console\CommandCollection $commands The command collection to update
     * @return \Cake\Console\CommandCollection
     * @since 1.7.1
     */
    public function console($commands): CommandCollection
    {
        return $commands->add('thumber.clear', ClearCommand::class)
            ->add('thumber.clear_all', ClearAllCommand::class);
    }
}
