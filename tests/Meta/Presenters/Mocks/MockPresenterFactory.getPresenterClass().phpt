<?php
namespace Instante\Tests\Meta\Presenters\Mocks;

use Instante\Tests\Presenters\Mocks\MockPresenterFactory;
use Instante\Tests\TestBootstrap;
use Nette\Application\UI\InvalidLinkException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

TestBootstrap::prepareUnitTest();

$mus = new MockPresenterFactory;

$mus->presenterClassMap['Bar'] = 'Baz';
$p = 'Bar';
Assert::same('Baz', $mus->getPresenterClass($p));

Assert::exception(function () use ($mus) {
    $pp = 'Baz';
    $mus->getPresenterClass($pp);
}, InvalidLinkException::class);
