<?php
namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use Instante\Tests\Utils\MockStatic;
use Nette\InvalidStateException;
use Tester\Assert;

require_once '../../vendor/autoload.php';

$mockEnvironment = MockStatic::mock('Tester\Environment', ['checkAssertions']);
$mockEnvironment->shouldReceive('setup')->once();

require __DIR__ . '/../../src/test-bootstrap.php';

TestBootstrap::$tempDir = __DIR__ . '/../temp';
TestBootstrap::prepareUnitTest($testsDir = __DIR__ . '/../sandbox/tests');

//test default paths
Assert::same($testsDir . '/..', TestBootstrap::$rootDir);
Assert::same($testsDir . '/../app', TestBootstrap::$appDir);
Assert::same($testsDir . '/../vendor', TestBootstrap::$vendorDir);
Assert::same($testsDir, TestBootstrap::$testsDir);
