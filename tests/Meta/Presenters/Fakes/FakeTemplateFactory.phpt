<?php
namespace Instante\Tests\Meta\Presenters\Fakes;

use Instante\Tests\Presenters\Fakes\FakeTemplate;
use Instante\Tests\Presenters\Fakes\FakeTemplateFactory;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

$mtf = new FakeTemplateFactory();
$t = $mtf->createTemplate();
Assert::type(FakeTemplate::class, $t);
$t->{'pipe'} = $pipe = 'Ceci n`est pas une modèle.';
Assert::same($pipe, $t->{'pipe'});

Assert::exception(function () use ($t) {
    $t->{'pasPipe'};
}, InvalidArgumentException::class, "The variable 'pasPipe' does not exist in template.");


Assert::exception(function () use ($t) {
    $t->render();
}, InvalidStateException::class, 'Please set a template file first');

$t->setFile(__DIR__ . '/foo.latte');
Assert::same('{$foo} {$bar}', trim($t->renderToString()));

ob_start();
$t->render();
Assert::same('{$foo} {$bar}', trim(ob_get_clean()));


/** @noinspection PhpUndefinedMethodInspection */
$t->doStuff('foo', 'bar');
/** @noinspection PhpUndefinedMethodInspection */
$t->doOtherStuff();
Assert::equal([
    ['doStuff', ['foo', 'bar']],
    ['doOtherStuff', []],
], $t->getCalledMethods());

