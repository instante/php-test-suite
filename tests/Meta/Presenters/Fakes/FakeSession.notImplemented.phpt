<?php
namespace Instante\Tests\Meta\Presenters\Fakes;

use Instante\Tests\Presenters\Fakes\FakeSession;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\NotImplementedException;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

//everything except getSection and hasSection is not implemented


$ms = new FakeSession;
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection because of ISessionStorage */
/** @noinspection PhpDeprecationInspection because of ISessionStorage */
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
    'setStorage' => [mock(\Nette\Http\ISessionStorage::class)],
    'setHandler' => [mock(\SessionHandlerInterface::class)],
];

foreach ($methods as $method => $args) {
    Assert::exception(function () use ($method, $args, $ms) {
        call_user_func_array([$ms, $method], $args);
    }, NotImplementedException::class);
}
