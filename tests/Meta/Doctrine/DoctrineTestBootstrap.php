<?php

namespace Instante\Tests\Meta;

use Nette\Configurator;

class DoctrineTestBootstrap extends SandboxTestBootstrap
{
    protected static function configureConfigurator(Configurator $configurator, $configDir)
    {
        parent::configureConfigurator($configurator, $configDir);
        $doctrineConfig = static::$configDir . '/doctrine.neon';
        $dbLocalNeon = static::$configDir . '/_db_local.neon';
        $configurator->addConfig($doctrineConfig);

        if (is_file($dbLocalNeon)) {
            $configurator->addConfig($dbLocalNeon);
        }
    }
}
