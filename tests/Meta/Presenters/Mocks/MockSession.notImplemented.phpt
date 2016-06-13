<?php
namespace Instante\Tests\Meta\Presenters\Mocks;

use Instante\Tests\Presenters\Mocks\MockSession;
use Instante\Tests\TestBootstrap;
use Nette\NotImplementedException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';
require __DIR__ . '/dummySessionClasses.inc';

TestBootstrap::prepareUnitTest();

//everything except getSection and hasSection is not implemented


$ms = new MockSession;
$methods = [
    'start' => [],
    'isStarted' => [],
    'close' => [],
    'destroy' => [],
    'exists' => [],
    'regenerateId' => [],
    'getId' => [],
    'setName' => ['a'],
    'getName' => [],
    'getIterator' => [],
    'clean' => [],
    'setOptions' => [[]],
    'setExpiration' => ['a'],
    'setCookieParameters' => ['a'],
    'getCookieParameters' => [],
    'setSavePath' => ['a'],
    'setStorage' => [new DummySessionStorage],
    'setHandler' => [new DummySessionHandler],
];

foreach ($methods as $method => $args) {
    Assert::exception(function () use ($method, $args, $ms) {
        call_user_func_array([$ms, $method], $args);
    }, NotImplementedException::class);
}
