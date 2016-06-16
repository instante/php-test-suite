<?php

namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use Nette\Loaders\RobotLoader;

class SandboxTestBootstrap extends TestBootstrap
{
    protected static function addRobotLoaderPaths(RobotLoader $loader)
    {
        // appDir not needed there because sandbox app is located under tests dir
        // we need to avoid adding appDir to prevent duplicate indexing by RobotLoader
        $loader->addDirectory(static::$testsDir);
    }
}
