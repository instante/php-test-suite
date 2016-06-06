<?php

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

require '../../src/test-bootstrap.php';
try {
    TestBootstrap::prepareUnitTest(__DIR__ . '/..');
    Assert::fail('should have crashed on missing app dir');
} catch (\Nette\IOException $ex) { //expected to fail on robot loader to non-existent default app dir
}
//test default paths
Assert::same(__DIR__ . '/../..', TestBootstrap::$rootDir);
Assert::same(__DIR__ . '/../../app', TestBootstrap::$appDir);
Assert::same(__DIR__ . '/../../vendor', TestBootstrap::$vendorDir);
Assert::same(__DIR__ . '/..', TestBootstrap::$testsDir);
Assert::same(__DIR__ . '/../temp', TestBootstrap::$tempDir);


//test exception on prepared twice
Assert::exception(function () {
    TestBootstrap::prepareUnitTest(__DIR__ . '/..');
}, InvalidStateException::class, 'Test environment already prepared');


//test is temp dir ready
Assert::true(is_dir(TestBootstrap::$tempDir) && is_writable(TestBootstrap::$tempDir), 'Prepared temp dir for tests');

//test Environment::setup() was called
Assert::true(MockEnvironment::$called, 'Environment::setup() called');
