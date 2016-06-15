<?php

namespace Instante\Tests\Meta\Doctrine;

use Instante\Tests\Doctrine\DoctrineTester;
use Instante\Tests\TestBootstrap;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
$context = TestBootstrap::prepareIntegrationTest();

$doctrineTester = DoctrineTester::createFromContainer($context);

$doctrineTester->prepareDatabaseTest();

Assert::true(TRUE);
