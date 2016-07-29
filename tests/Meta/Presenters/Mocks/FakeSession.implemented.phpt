<?php
namespace Instante\Tests\Meta\Presenters\Fakes;

use Instante\Tests\Presenters\Fakes\FakeSession;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

//only getSection and hasSection are implemented

$s = new FakeSession;
Assert::false($s->hasSection('aaa'));
$aaa = $s->getSection('aaa');
Assert::true($s->hasSection('aaa'));
Assert::same($aaa, $s->getSection('aaa'));
Assert::notSame($aaa, $s->getSection('bbb'));
