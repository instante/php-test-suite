<?php

namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use KdybyModule\CliPresenter;
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
        if (class_exists(CliPresenter::class)) {
            // avoid loading CliPresenter service without required Kdyby\Console\Application
            $configurator->addParameters(['application' => ['scanComposer' => FALSE]]);
        }
    }
}
