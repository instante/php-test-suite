<?php

namespace Instante\Tests;

use Mockery;
use Mockery\Expectation;
use Mockery\ExpectationDirector;
use Mockery\Mock;
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

    /** @var string temp directory for tests (defaults to $testsDir/temp) */
    public static $tempDir;

    /** @var string project root directory (defaults to $testsDir/..) */
    public static $rootDir;

    /** @var string nette application directory (defaults to $rootDir/app) */
    public static $appDir;

    /** @var string nette composer directory (defaults to $rootDir/vendor) */
    public static $vendorDir;

    /** @var string nette config directory (defaults to $appDir/config) */
    public static $configDir;

    protected static $prepared = FALSE;

    /** static class, cannot be instantiated */
    private function __construct() { }

    /**
     * Prepares common test environment, not dependent on testing Nette application:
     *
     *      - sets fixed request time and timezone
     *      - purges _GET, _POST, _ENV
     *      - calls Tester\Environment::setup()
     *      - ensures tests temp directory exists
     *      - validates Mockery expectations on shutdown
     *
     * @param string $tempDir
     */
    public static function prepareTestEnvironment($tempDir = NULL)
    {
        if ($tempDir !== NULL) {
            static::$tempDir = $tempDir;
        }

        static::checkPreparedOnce();
        static::unifyConfiguration();
        Environment::setup();
        static::prepareTempDir();
        static::prepareMockery();
    }

    /**
     * Prepares environment for unit tests in Nette application:
     *
     *      - calls prepareTestEnvironment() to prepare basics
     *      - auto-configures project paths relatively from tests directory
     *      - loads Composer autoloader and Nette RobotLoader
     *
     * @param string $testsDir
     */
    public static function prepareUnitTest($testsDir = NULL)
    {
        static::preparePaths($testsDir);
        require_once static::$vendorDir . '/autoload.php';
        static::prepareTestEnvironment();
        static::prepareRobotLoader();
    }

    /**
     * Prepares environment for integration tests in Nette application - loads
     * DI container with app configuration.
     *
     * @param string $testsDir
     * @return Container;
     */
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
            $configurator->addConfig("$configDir/local.neon");
        }
        $configurator->addConfig(['parameters' => ['database' => ['dbname' => '%database.dbname_test%']]]);
    }

    protected static function createConfigurator()
    {
        $configurator = new Configurator;
        foreach ($configurator->defaultExtensions as $name => $class) { // remove extensions from not installed packages
            if (is_array($class)) {
                $class = $class[0];
            }
            if (!class_exists($class)) {
                unset($configurator->defaultExtensions[$name]);
            }
        }
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
            static::addRobotLoaderPaths($loader);
            $loader->register();
        }
    }

    protected static function addRobotLoaderPaths(RobotLoader $loader)
    {
        $loader
            ->addDirectory(static::$appDir)
            ->addDirectory(static::$testsDir);
    }

    protected static function prepareTempDir()
    {
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
        if (static::$tempDir === NULL) {
            static::$tempDir = static::$testsDir . '/temp';
        }
    }

    protected static function unifyConfiguration()
    {
        date_default_timezone_set('Europe/Prague');

        $_SERVER['REQUEST_TIME'] = 1234567890;
        $_ENV = $_GET = $_POST = [];
    }

    protected static function prepareMockery()
    {
        register_shutdown_function(function () {
            static::ignoreAssertionsWhenExpectations();
            Mockery::close();
        });
    }

    /**
     * Do not check assertions were executed when there are constrainted mocks
     */
    protected static function ignoreAssertionsWhenExpectations()
    {
        /** @var Mock $mock */
        foreach (\Mockery::getContainer()->getMocks() as $mock) {
            /** @var ExpectationDirector $expectationDirector */

            foreach ($mock->mockery_getExpectations() as $expectationDirector) {
                $expectations = $expectationDirector->getExpectations();
                if (method_exists($expectationDirector, 'getDefaultExpectations')) {
                    //mockery <=0.9.5 compatibility
                    $expectations = array_merge($expectations, $expectationDirector->getDefaultExpectations());
                }
                /** @var Expectation $expectation */
                foreach ($expectations as $expectation) {
                    if ($expectation->isCallCountConstrained()) {
                        Environment::$checkAssertions = FALSE;
                        return;
                    }
                }
            }
        }
    }
}
