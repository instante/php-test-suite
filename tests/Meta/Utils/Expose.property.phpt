<?php
namespace Instante\Tests\Meta\Presenters\Helpers;

use Instante\Tests\Meta\SandboxTestBootstrap;
use Instante\Tests\Utils\Expose;
use Tester\Assert;

class Foo
{
    private $a;

    public function getA()
    {
        return $this->a;
    }
}

require __DIR__ . '/../../bootstrap.php';
SandboxTestBootstrap::prepareUnitTest();

$x = new Expose(new Foo);
$x->a = TRUE;
Assert::true($x->a);
Assert::true($x->getA());

Assert::true(isset($x->a));
Assert::false(isset($x->z));
