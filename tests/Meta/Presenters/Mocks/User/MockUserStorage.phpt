<?php
namespace Instante\Tests\Meta\Presenters\Mocks\User;

use Instante\Tests\Presenters\Mocks\User\MockUserStorage;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Security\Identity;
use Nette\Security\IUserStorage;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

SandboxTestBootstrap::prepareUnitTest();

$mus = new MockUserStorage;
Assert::false($mus->isAuthenticated());
Assert::null($mus->getIdentity());
Assert::null($mus->getLogoutReason());

$mus->setLogoutReason(IUserStorage::INACTIVITY);
Assert::same(IUserStorage::INACTIVITY, $mus->getLogoutReason());

$mus->setAuthenticated(TRUE);
Assert::true($mus->isAuthenticated());

$mus->setIdentity(new Identity(1));
Assert::type(Identity::class, $mus->getIdentity());
