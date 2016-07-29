<?php
namespace Instante\Tests\Meta\Presenters\Fakes\User;

use Instante\Tests\Presenters\Fakes\User\FakeUserStorage;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Security\Identity;
use Nette\Security\IUserStorage;
use Tester\Assert;

require __DIR__ . '/../../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

$mus = new FakeUserStorage;
Assert::false($mus->isAuthenticated());
Assert::null($mus->getIdentity());
Assert::null($mus->getLogoutReason());

$mus->setLogoutReason(IUserStorage::INACTIVITY);
Assert::same(IUserStorage::INACTIVITY, $mus->getLogoutReason());

$mus->setAuthenticated(TRUE);
Assert::true($mus->isAuthenticated());

$mus->setIdentity(new Identity(1));
Assert::type(Identity::class, $mus->getIdentity());
