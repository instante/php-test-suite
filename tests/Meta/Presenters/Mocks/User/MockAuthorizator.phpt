<?php
namespace Instante\Tests\Meta\Presenters\Mocks\User;

use Instante\Tests\Presenters\Mocks\User\MockAuthorizator;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Tester\Assert;

require __DIR__ . '/../../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

$ma = new MockAuthorizator;
$ma->acl = [
    'fooRole' => [
        'xResource' =>
            ['yPrivilege'],
    ],
];

Assert::true($ma->isAllowed('fooRole', 'xResource', 'yPrivilege'));
Assert::false($ma->isAllowed('barRole', 'xResource', 'yPrivilege'));
$ma->allow('barRole', 'xResource', 'yPrivilege');
Assert::true($ma->isAllowed('barRole', 'xResource', 'yPrivilege'));
$ma->allow('bazRole', 'aResource', 'bPrivilege');
Assert::true($ma->isAllowed('bazRole', 'aResource', 'bPrivilege'));
