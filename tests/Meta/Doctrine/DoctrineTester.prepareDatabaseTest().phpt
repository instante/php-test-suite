<?php

namespace Instante\Tests\Meta\Doctrine;

use Instante\Tests\Doctrine\DoctrineTester;
use Instante\Tests\Meta\DoctrineTestBootstrap;
use Instante\Tests\Sandbox\SampleEntity;
use Kdyby\Doctrine\EntityManager;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . '/../../bootstrap.php';

if (!class_exists(EntityManager::class)) {
    Environment::skip('Install instante/doctrine to enable database tests.');
}

require __DIR__ . '/DoctrineTestBootstrap.php';

$context = DoctrineTestBootstrap::prepareIntegrationTest();

$doctrineTester = DoctrineTester::createFromContainer($context);

$doctrineTester->prepareDatabaseTest();

/** @var EntityManager $em */
$em = $context->getByType(EntityManager::class);
Assert::same(0, (int)$em->getRepository(SampleEntity::class)->countBy());
$em->persist(new SampleEntity('foo'));
$em->flush();
Assert::same(1, (int)$em->getRepository(SampleEntity::class)->countBy());
