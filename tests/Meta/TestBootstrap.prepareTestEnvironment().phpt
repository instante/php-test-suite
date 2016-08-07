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
TestBootstrap::prepareTestEnvironment($testsDir = __DIR__ . '/../sandbox/tests');

//test exception on prepared twice
Assert::exception(function () {
    TestBootstrap::prepareUnitTest(__DIR__ . '/..');
}, InvalidStateException::class, 'Test environment already prepared');

//test is temp dir ready
Assert::true(is_dir(TestBootstrap::$tempDir) && is_writable(TestBootstrap::$tempDir), 'Prepared temp dir for tests');

//test environment clean
Assert::count(0, $_ENV);
Assert::count(0, $_GET);
Assert::count(0, $_POST);
