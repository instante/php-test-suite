<?php

namespace Instante\Tests\Presenters\Helpers;

use Instante\Tests\Presenters\DI\PrimaryDependencyContainer;
use Instante\Tests\Presenters\Mocks\Latte\DeferredTemplateFactory;
use Instante\Tests\Presenters\Mocks\SimpleLatteFactory;
use Instante\Tests\Presenters\PresenterTester;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Caching\Storages\DevNullStorage;
use Nette\StaticClassException;

class LatteConfigurator
{
    public function __construct() { throw new StaticClassException; }

    public static function configurePDC(PrimaryDependencyContainer $pdc)
    {
        $pdc->setTemplateFactory(new DeferredTemplateFactory(function () use ($pdc) {
            return new TemplateFactory(
                new SimpleLatteFactory,
                $pdc->getUsedHttpRequest(),
                $pdc->getHttpResponse(),
                $pdc->getUserService(),
                new DevNullStorage
            );
        }));
    }

    public static function configureTester(PresenterTester $presenterTester)
    {
        self::configurePDC($presenterTester->getPrimaryDependencyContainer());
    }
}
