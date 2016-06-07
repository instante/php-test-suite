<?php

namespace Instante\Tests;

use Nette\DI\Container;
use Nette\InvalidStateException;
use Tester\Environment;
use Nette\Loaders\RobotLoader;
use Nette\Caching\Storages\FileStorage;
use Nette\Configurator;

class TestBootstrap
{
    /** @var string tests directory */
    public static $testsDir;

    /** @var string temp directory for tests (always $testsDir/temp) */
    public static $tempDir;

    /** @var string project root directory (defaults to $testsDir/..) */
    public static $rootDir;

    /** @var string nette application directory (defaults to $rootDir/app) */
    public static $appDir;

    /** @var string nette composer directory (defaults to $rootDir/vendor) */
    public static $vendorDir;

    private static $prepared = FALSE;

    public static function prepareUnitTest($testsDir, $rootDir = NULL, $appDir = NULL, $vendorDir = NULL)
    {
        static::checkPreparedOnce();
        static::preparePaths($testsDir, $rootDir, $appDir, $vendorDir);
        require static::$vendorDir . '/autoload.php';
        Environment::setup();

        static::prepareTempDir();
        static::prepareRobotLoader();
    }

    /**
     * @param string $testsDir
     * @param string $rootDir
     * @param string $appDir
     * @param string $vendorDir
     * @param string $configDir
     * @return Container
     */
    public static function prepareIntegrationTest(
        $testsDir,
        $rootDir = NULL,
        $appDir = NULL,
        $vendorDir = NULL,
        $configDir = NULL
    ) {

        static::prepareUnitTest($testsDir, $rootDir, $appDir, $vendorDir);
        if ($configDir === NULL) {
            $configDir = static::$appDir . '/config';
        }

        $configurator = static::createConfigurator();
        static::configureConfigurator($configurator, $configDir);

        return $configurator->createContainer();
    }

    protected static function configureConfigurator(Configurator $configurator, $configDir)
    {
        $configurator->addParameters([
            'appDir' => static::$appDir,
            'paths' => [
                'root' => static::$rootDir,
                'log' => static::$rootDir . '/log',
            ],
        ]);

        $configurator->addConfig("$configDir/default.neon");
        if (file_exists("$configDir/local.neon")) {
            $configurator->addConfig("$configDir/local.neon", $configurator::NONE);
        }
        $configurator->addConfig(['doctrine' => ['dbname' => '%database.dbname_test%']]);
    }

    protected static function createConfigurator()
    {
        $configurator = new Configurator;
        $configurator->setTempDirectory(static::$tempDir);
        return $configurator;
    }

    protected static function prepareRobotLoader()
    {
        if (class_exists(RobotLoader::class)) {
            $loader = new RobotLoader;
            if (class_exists(FileStorage::class)) {
                $loader->setCacheStorage(new FileStorage(static::$tempDir));
            }
            $loader
                ->addDirectory(static::$appDir)
                ->addDirectory(static::$testsDir)
                ->register();
        }
    }

    protected static function prepareTempDir()
    {
        static::$tempDir = static::$testsDir . '/temp';
        @mkdir(static::$tempDir . '/cache', 0777, TRUE); // @ - dir may already exist
    }

    protected static function checkPreparedOnce()
    {
        if (static::$prepared) {
            throw new InvalidStateException('Test environment already prepared');
        }
        static::$prepared = TRUE;
    }

    protected static function preparePaths($testsDir, $rootDir = NULL, $appDir = NULL, $vendorDir = NULL)
    {
        if ($rootDir === NULL) {
            $rootDir = $testsDir . '/..';
        }
        if ($vendorDir === NULL) {
            $vendorDir = $rootDir . '/vendor';
        }
        if ($appDir === NULL) {
            $appDir = $rootDir . '/app';
        }
        static::$rootDir = $rootDir;
        static::$appDir = $appDir;
        static::$testsDir = $testsDir;
        static::$vendorDir = $vendorDir;
    }
}
