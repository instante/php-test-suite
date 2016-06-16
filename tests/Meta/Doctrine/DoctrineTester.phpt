<?php

namespace Instante\Tests\Meta\Doctrine;

use Instante\Tests\Doctrine\DoctrineTester;
use Instante\Tests\Sandbox\SampleEntity;
use Instante\Tests\TestBootstrap;
use Kdyby\Doctrine\EntityManager;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
$context = TestBootstrap::prepareIntegrationTest();

$doctrineTester = DoctrineTester::createFromContainer($context);

$doctrineTester->prepareDatabaseTest();

/** @var EntityManager $em */
$em = $context->getByType(EntityManager::class);
Assert::same(0, $em->getRepository(SampleEntity::class)->countBy());
$em->persist(new SampleEntity('foo'));
$em->flush();
Assert::same(1, $em->getRepository(SampleEntity::class)->countBy());
