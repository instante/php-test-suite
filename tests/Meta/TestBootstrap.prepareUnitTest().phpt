<?php
namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use Nette\InvalidStateException;
use Tester\Assert;

class MockEnvironment
{
    public static $called = FALSE;

    public static function setup()
    {
        self::$called = TRUE;
    }
}

class_alias(MockEnvironment::class, 'Tester\Environment');

require __DIR__ . '/../../src/test-bootstrap.php';
TestBootstrap::prepareUnitTest($testsDir = __DIR__ . '/../sandbox/tests');

//test default paths
Assert::same($testsDir . '/..', TestBootstrap::$rootDir);
Assert::same($testsDir . '/../app', TestBootstrap::$appDir);
Assert::same($testsDir . '/../vendor', TestBootstrap::$vendorDir);
Assert::same($testsDir, TestBootstrap::$testsDir);
Assert::same($testsDir . '/temp', TestBootstrap::$tempDir);


//test exception on prepared twice
Assert::exception(function () {
    TestBootstrap::prepareUnitTest(__DIR__ . '/..');
}, InvalidStateException::class, 'Test environment already prepared');


//test is temp dir ready
Assert::true(is_dir(TestBootstrap::$tempDir) && is_writable(TestBootstrap::$tempDir), 'Prepared temp dir for tests');

//test Environment::setup() was called
Assert::true(MockEnvironment::$called, 'Environment::setup() called');
