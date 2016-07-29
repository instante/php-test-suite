<?php
namespace Instante\Tests\Meta\Presenters\Fakes;

use Instante\Tests\Presenters\Fakes\FakePresenterFactory;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Application\UI\InvalidLinkException;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

$mus = new FakePresenterFactory;

$mus->presenterClassMap['Bar'] = 'Baz';
$p = 'Bar';
Assert::same('Baz', $mus->getPresenterClass($p));

Assert::exception(function () use ($mus) {
    $pp = 'Baz';
    $mus->getPresenterClass($pp);
}, InvalidLinkException::class);
