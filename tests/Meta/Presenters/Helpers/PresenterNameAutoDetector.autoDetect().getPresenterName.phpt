<?php
namespace Instante\Tests\Meta\Presenters\Helpers;

use Instante\Tests\Presenters\DI\PrimaryDependencyContainer;
use Instante\Tests\Presenters\Helpers\PresenterNameAutoDetector;
use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\TestBootstrap;
use Nette\DI\Container;
use Tester\Assert;

require '../../../bootstrap.php';

TestBootstrap::prepareUnitTest();

Assert::same(':Foo', PresenterNameAutoDetector::autoDetect('FooPresenter'));
Assert::same(':Foo', PresenterNameAutoDetector::autoDetect('Foo\Bar\Baz\FooPresenter'));
Assert::same(':Bar:Baz:Foo', PresenterNameAutoDetector::autoDetect('Foo\BarModule\BazModule\Presenters\FooPresenter'));

