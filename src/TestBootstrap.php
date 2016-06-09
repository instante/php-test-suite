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

    /** static class, cannot be instantiated */
    private function __construct() { }


    public static function prepareUnitTest($testsDir = NULL)
    {
        static::checkPreparedOnce();
        static::unifyConfiguration();
        static::preparePaths($testsDir);
        require static::$vendorDir . '/autoload.php';
        Environment::setup();

        static::prepareTempDir();
        static::prepareRobotLoader();
    }

    public static function prepareIntegrationTest($testsDir = NULL)
    {

        static::prepareUnitTest($testsDir);
        if (static::$configDir === NULL) {
            static::$configDir = static::$appDir . '/config';
        }

        $configurator = static::createConfigurator();
        static::configureConfigurator($configurator, static::$configDir);

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

    protected static function preparePaths($testsDir = NULL)
    {
        if ($testsDir !== NULL) {
            static::$testsDir = $testsDir;
        }
        if (static::$testsDir === NULL) {
            throw new InvalidStateException(__CLASS__ . '::$testsDir has to be set');
        }
        if (static::$rootDir === NULL) {
            static::$rootDir = static::$testsDir . '/..';
        }
        if (static::$vendorDir === NULL) {
            static::$vendorDir = static::$rootDir . '/vendor';
        }
        if (static::$appDir === NULL) {
            static::$appDir = static::$rootDir . '/app';
        }
    }

    protected static function unifyConfiguration()
    {
        date_default_timezone_set('Europe/Prague');

        $_SERVER['REQUEST_TIME'] = 1234567890;
        $_ENV = $_GET = $_POST = [];
    }
}
