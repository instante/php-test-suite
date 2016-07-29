<?php

namespace Instante\Tests\Meta\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Instante\Tests\Doctrine\DoctrineTester;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Kdyby\Doctrine\EntityManager;

require __DIR__ . '/../../bootstrap.php';

SandboxTestBootstrap::prepareUnitTest();

$mockEntityManager = mock(EntityManager::class, [
    'getConnection' => mock(Connection::class, [
        'getSchemaManager->listTableNames' => [],
        'prepare->execute' => TRUE,
    ]),
]);
$mockEntityManager->shouldReceive('clear')->once();
$mockEntityManager->shouldIgnoreMissing();

$doctrineTester = new DoctrineTester($mockEntityManager, mock(Configuration::class), '');

$doctrineTester->clearDatabase();
