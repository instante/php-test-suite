<?php

namespace Instante\Tests\Meta\Doctrine;

use Instante\Tests\Doctrine\DoctrineTestCase;
use Instante\Tests\Doctrine\DoctrineTester;
use Instante\Tests\Meta\DoctrineTestBootstrap;
use Kdyby\Doctrine\EntityManager;
use Mockery;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . '/../../bootstrap.php';

if (!class_exists(EntityManager::class)) {
    Environment::skip('Install nette/application to enable presenter tests.');
}

require __DIR__ . '/DoctrineTestBootstrap.php';
DoctrineTestBootstrap::prepareUnitTest();

class DoctrineTestCaseTest extends DoctrineTestCase
{
    public static $numberOfPreparation = 0;

    public function testFoo()
    {
        Assert::true(TRUE);
    }

    public function testBar()
    {
        Assert::true(TRUE);
    }

    public function testBaz()
    {
        Assert::true(TRUE);
    }
}

$mockDoctrineTester = mock(DoctrineTester::class);
$mockDoctrineTester->shouldReceive('prepareDatabaseTest')->once();
$mockDoctrineTester->shouldReceive('clearDatabase')->times(5);
$mockDoctrineTester->shouldReceive('getEntityManager');
(new DoctrineTestCaseTest($mockDoctrineTester))->run();
