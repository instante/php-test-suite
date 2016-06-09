<?php
namespace Instante\Tests\Meta\Presenters\Mocks;

use Instante\Tests\Presenters\Mocks\MockSession;
use Instante\Tests\TestBootstrap;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

TestBootstrap::prepareUnitTest();

//only getSection and hasSection are implemented

$s = new MockSession;
Assert::false($s->hasSection('aaa'));
$aaa = $s->getSection('aaa');
Assert::true($s->hasSection('aaa'));
Assert::same($aaa, $s->getSection('aaa'));
Assert::notSame($aaa, $s->getSection('bbb'));
