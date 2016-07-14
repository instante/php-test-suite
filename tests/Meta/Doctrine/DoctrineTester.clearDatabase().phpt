<?php

namespace Instante\Tests\Meta\Doctrine;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\ORM\Configuration as ORMConfiguration;
use Instante\Tests\Doctrine\DoctrineTester;
use Instante\Tests\Meta\DoctrineTestBootstrap;
use Kdyby\Doctrine\EntityManager;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . '/../../bootstrap.php';

if (!class_exists(EntityManager::class)) {
    Environment::skip('Install instante/doctrine to enable database tests.');
}

class MockEMClearDatabase extends EntityManager
{
    public $calledClear = FALSE;

    public function clear($entityName = NULL)
    {
        $entityName === NULL && $this->calledClear = TRUE;
    }

    public function __construct(
        Connection $conn,
        ORMConfiguration $config,
        EventManager $eventManager
    ) {
        parent::__construct($conn, $config, $eventManager);
    }

}

require __DIR__ . '/DoctrineTestBootstrap.php';
$context = DoctrineTestBootstrap::prepareIntegrationTest();
/** @var EntityManager $em */
$em = $context->getByType(EntityManager::class);
$mem = new MockEMClearDatabase($em->getConnection(), $em->getConfiguration(), $em->getEventManager());
/** @var Configuration $conf */
$conf = $context->getByType(Configuration::class);
$temp = $context->getParameters()['tempDir'];

$doctrineTester = new DoctrineTester($mem, $conf, $temp);

Assert::false($mem->calledClear);
$doctrineTester->clearDatabase();
Assert::true($mem->calledClear);
