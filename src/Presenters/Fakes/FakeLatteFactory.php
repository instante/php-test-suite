<?php

namespace Instante\Tests\Presenters\Fakes;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

class FakeLatteFactory implements ILatteFactory
{
    public function create()
    {
        return new Engine;
    }
}
