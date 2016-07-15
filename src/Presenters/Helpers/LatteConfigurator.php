<?php

namespace Instante\Tests\Presenters\Helpers;

use Instante\Tests\Presenters\DI\PrimaryDependencyContainer;
use Instante\Tests\Presenters\Mocks\Latte\DeferredTemplateFactory;
use Instante\Tests\Presenters\Mocks\SimpleLatteFactory;
use Instante\Tests\Presenters\PresenterTester;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Caching\Storages\DevNullStorage;
use Nette\Http\IResponse;
use Nette\StaticClassException;

class LatteConfigurator
{
    public function __construct() { throw new StaticClassException; }

    public static function configurePDC(PrimaryDependencyContainer $pdc)
    {
        $pdc->setTemplateFactory(new DeferredTemplateFactory(function () use ($pdc) {
            $refl = new \ReflectionClass(TemplateFactory::class);
            if ($refl->getConstructor()->getParameters()[2]->getClass()->getName() === IResponse::class
            ) { // Latte < 2.4
                return new TemplateFactory(
                    new SimpleLatteFactory,
                    $pdc->getUsedHttpRequest(),
                    $pdc->getHttpResponse(),
                    $pdc->getUserService(),
                    new DevNullStorage
                );
            } else { // Latte >= 2.4
                return new TemplateFactory(
                    new SimpleLatteFactory,
                    $pdc->getUsedHttpRequest(),
                    $pdc->getUserService(),
                    new DevNullStorage
                );
            }
        }));
    }

    public static function configureTester(PresenterTester $presenterTester)
    {
        self::configurePDC($presenterTester->getPrimaryDependencyContainer());
    }
}
