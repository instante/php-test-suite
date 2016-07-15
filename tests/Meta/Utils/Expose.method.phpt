<?php
namespace Instante\Tests\Meta\Presenters\Helpers;

use Instante\Tests\Meta\SandboxTestBootstrap;
use Instante\Tests\Utils\Expose;
use Tester\Assert;

class Foo
{
    private function a()
    {
        return TRUE;
    }
}

require __DIR__ . '/../../bootstrap.php';
SandboxTestBootstrap::prepareUnitTest();

$x = new Expose(new Foo);
Assert::true($x->a());
