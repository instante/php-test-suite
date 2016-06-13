<?php
namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use Nette\DI\Container;
use Tester\Assert;

require __DIR__ . '/../../src/test-bootstrap.php';

$container = TestBootstrap::prepareIntegrationTest(__DIR__ . '/../sandbox/tests');

Assert::type(Container::class, $container, 'container created');

Assert::same('bar', $container->getParameters()['database']['dbname'], 'dbname_test copied to dbname');
Assert::same(TestBootstrap::$tempDir, $container->getParameters()['tempDir'], 'tempDir passed to container');
Assert::true($container->getParameters()['localNeon'], 'local.neon loaded');
