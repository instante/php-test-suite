<?php
namespace Instante\Tests\Meta\Presenters;

use Instante\Tests\Presenters\Helpers\LatteConfigurator;
use Instante\Tests\Presenters\PresenterTester;
use Instante\Tests\TestBootstrap;
use Nette;
use Nette\Application\UI\Presenter;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';
TestBootstrap::prepareUnitTest();

class LatteTestPresenter extends Presenter
{
    public function renderDefault()
    {
        $this->template->{'w'} = 'world';
    }
}

$pt = new PresenterTester(new LatteTestPresenter, TestBootstrap::$tempDir, 'Homepage');
LatteConfigurator::configureTester($pt);

$result = $pt->runPresenter();

Assert::same('Hello world', trim($result->getResponseBody()));
