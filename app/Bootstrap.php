<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;

class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator();
        $appDir = dirname(__DIR__);

        // $configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
        // $configurator->setDebugMode(false); // disable debug mode
        $configurator->enableTracy($appDir.'/log');

        $configurator->setTempDirectory($appDir.'/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();
        $configurator->setTimeZone('Europe/Moscow');

        $configurator->addConfig($appDir.'/config/all_configs.neon');
        /*
        $configurator->addConfig($appDir.'/config/common.neon');
        $configurator->addConfig($appDir.'/config/db.neon');
        $configurator->addConfig($appDir.'/config/services.neon');
        $configurator->addConfig($appDir.'/config/webpack.neon');
        $configurator->addConfig($appDir.'/config/own.neon');
        */

        return $configurator;
    }
}
