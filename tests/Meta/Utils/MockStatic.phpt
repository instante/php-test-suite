<?php
namespace Instante\Tests\Meta\Presenters\Helpers;

use /** @noinspection PhpUndefinedNamespaceInspection */
    /** @noinspection PhpUndefinedClassInspection */
    A\B\C;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Instante\Tests\Utils\MockStatic;

require __DIR__ . '/../../bootstrap.php';
SandboxTestBootstrap::prepareUnitTest();

function foo()
{
    /** @noinspection PhpUndefinedClassInspection */
    C::$prologue = 'helloworld';
    /** @noinspection PhpUndefinedClassInspection */
    C::$epilogue = 'byeworld';
    /** @noinspection PhpUndefinedClassInspection */
    C::hello();
}

$mock = MockStatic::mock('A\B\C', ['prologue', 'epilogue']);
$mock->shouldReceive('hello')->once();
foo();
