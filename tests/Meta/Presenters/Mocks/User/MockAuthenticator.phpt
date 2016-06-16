<?php
namespace Instante\Tests\Meta\Presenters\Mocks\User;

use Instante\Tests\Presenters\Mocks\User\MockAuthenticator;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;
use Tester\Assert;

require __DIR__ . '/../../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

class MockMooIdentity implements IIdentity
{
    function getId()
    {
        return 1;
    }

    function getRoles()
    {
        return ['mooRole'];
    }
}

$ma = new MockAuthenticator;
$ma->userList = [
    'foo' => 'bar',
    'moo' => ['boo', new MockMooIdentity],
    'zoo' => 'baz',
];
$ma->usersRoles = [
    'foo' => 'fooRole',
];

//standard user=>password auth, no role
$i = $ma->authenticate(['zoo', 'baz']);
Assert::type(IIdentity::class, $i);
Assert::same('zoo', $i->getId());
Assert::equal([], $i->getRoles());

//user with given identity
$i2 = $ma->authenticate(['moo', 'boo']);
Assert::type(MockMooIdentity::class, $i2);
Assert::equal(['mooRole'], $i2->getRoles());

//user with given role
$i3 = $ma->authenticate(['foo', 'bar']);
Assert::type(IIdentity::class, $i3);
Assert::equal(['fooRole'], $i3->getRoles());

//user not found
Assert::exception(function () use ($ma) {
    $ma->authenticate(['fooo', 'bar']);
}, AuthenticationException::class, "User 'fooo' not found.", IAuthenticator::IDENTITY_NOT_FOUND);

//bad password
Assert::exception(function () use ($ma) {
    $ma->authenticate(['foo', 'barr']);
}, AuthenticationException::class, 'Invalid password.', IAuthenticator::INVALID_CREDENTIAL);
