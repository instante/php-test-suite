<?php

namespace Instante\Tests\Presenters\Fakes;

use Nette\Application\IPresenterFactory;
use Nette\Application\UI\InvalidLinkException;
use Nette\Application\UI\Presenter;
use Nette\NotImplementedException;

class FakePresenterFactory implements IPresenterFactory
{
    public $presenterClassMap = [];

    public function getPresenterClass(& $name)
    {
        if (!isset($this->presenterClassMap[$name])) {
            throw new InvalidLinkException;
        }
        return $this->presenterClassMap[$name];
    }

    public function createPresenter($name)
    {
        throw new NotImplementedException(Presenter::class . ' does not use ::createPresenter() method. This fake'
            . ' implementation serves only as Presenter\'s internal dependency.');
    }
}
