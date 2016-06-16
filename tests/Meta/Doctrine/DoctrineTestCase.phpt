<?php

namespace Instante\Tests\Meta\Doctrine;

use Instante\Tests\Doctrine\DoctrineTestCase;
use Instante\Tests\Doctrine\DoctrineTester;
use Kdyby\Doctrine\EntityManager;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . '/../../bootstrap.php';

if (!class_exists(EntityManager::class)) {
    Environment::skip('Install nette/application to enable presenter tests.');
}

require __DIR__ . '/DoctrineTestBootstrap.php';


class DummyEntityManager extends EntityManager
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
    }
}

class MockDoctrineTester extends DoctrineTester
{
    public $prepareDatabaseTestCalls = 0;
    public $clearDatabaseCalls = 0;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
    }

    public function prepareDatabaseTest()
    {
        $this->prepareDatabaseTestCalls++;
    }

    public function clearDatabase()
    {
        $this->clearDatabaseCalls++;
    }

    public function getEntityManager()
    {
        return new DummyEntityManager;
    }

}

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

$mdt = new MockDoctrineTester;
(new DoctrineTestCaseTest($mdt))->run();

Assert::same(5, $mdt->clearDatabaseCalls); //3 in tearDown, 2 in setUp
Assert::same(1, $mdt->prepareDatabaseTestCalls); //called only for first test
