<?php

namespace Instante\Tests\Presenters\Mocks;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

class SimpleLatteFactory implements ILatteFactory
{
    public function create()
    {
        return new Engine;
    }
}
