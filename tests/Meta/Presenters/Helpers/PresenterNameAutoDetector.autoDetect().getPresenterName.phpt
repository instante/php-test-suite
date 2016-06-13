<?php
namespace Instante\Tests\Meta\Presenters\Helpers;

use Instante\Tests\Presenters\Helpers\PresenterNameAutoDetector;
use Instante\Tests\TestBootstrap;
use Nette\InvalidArgumentException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

TestBootstrap::prepareUnitTest();

Assert::same(':Foo', PresenterNameAutoDetector::autoDetect('FooPresenter'));
Assert::same(':Foo', PresenterNameAutoDetector::autoDetect('Foo\Bar\Baz\FooPresenter'));
Assert::same(':Bar:Baz:Foo', PresenterNameAutoDetector::autoDetect('Foo\BarModule\BazModule\Presenters\FooPresenter'));

Assert::exception(function () {
    PresenterNameAutoDetector::autoDetect('Foo\Bar\Baz\Foo');
}, InvalidArgumentException::class, 'Cannot autodetect presenter name, class is not named "XxxPresenter"');

