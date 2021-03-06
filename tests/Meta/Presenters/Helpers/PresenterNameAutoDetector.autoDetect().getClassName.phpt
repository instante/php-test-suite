<?php
namespace Instante\Tests\Meta\Presenters\Helpers;

use Instante\Tests\Presenters\Helpers\PresenterNameAutoDetector;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Application\UI\Presenter;
use Nette\InvalidArgumentException;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

class FooPresenter extends Presenter
{
}

SandboxTestBootstrap::prepareUnitTest();

Assert::same(':Foo', PresenterNameAutoDetector::autoDetect('FooPresenter'));
Assert::same(':Foo', PresenterNameAutoDetector::autoDetect(new FooPresenter));
Assert::same(':Foo', PresenterNameAutoDetector::autoDetect(function () { return new FooPresenter; }));
Assert::exception(function() {
    PresenterNameAutoDetector::autoDetect(123);
}, InvalidArgumentException::class, '$presenterCreator must be instance of ' . Presenter::class
    . ', presenter class name or callable factory');

