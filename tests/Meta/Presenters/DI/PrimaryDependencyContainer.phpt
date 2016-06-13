<?php
namespace Instante\Tests\Meta\Presenters\DI;

use Instante\Tests\Presenters\DI\PrimaryDependencyContainer;
use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\TestBootstrap;
use Nette\Application\UI\Presenter;
use Nette\DI\Container;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class MockSimplePresenter extends Presenter
{
}

TestBootstrap::prepareUnitTest();

$rb = new RequestBuilder('Foo', TestBootstrap::$tempDir . '/uploads');
$pdc = new PrimaryDependencyContainer($rb);
$pdc->setContext($context = new Container);

$p = new MockSimplePresenter;
$pdc->injectTo($p);
Assert::same($pdc->getUserStorage(), $p->getUser()->getStorage());
/** @noinspection PhpDeprecationInspection */
Assert::same($context, $p->getContext());
Assert::same($pdc->getSession(), $p->getSession());

