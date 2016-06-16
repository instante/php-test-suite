<?php

namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use Kdyby\Console\DI\ConsoleExtension;
use Nette\Configurator;
use Nette\Loaders\RobotLoader;

class SandboxTestBootstrap extends TestBootstrap
{
    protected static function addRobotLoaderPaths(RobotLoader $loader)
    {
        // appDir not needed there because sandbox app is located under tests dir
        // we need to avoid adding appDir to prevent duplicate indexing by RobotLoader
        $loader->addDirectory(static::$testsDir);
    }

    protected static function configureConfigurator(Configurator $configurator, $configDir)
    {
        parent::configureConfigurator($configurator, $configDir);
        if (class_exists(ConsoleExtension::class)) {
            // register console extension only when loaded,
            // needed for Nette <=2.3
            ConsoleExtension::register($configurator);
        }
    }
}
