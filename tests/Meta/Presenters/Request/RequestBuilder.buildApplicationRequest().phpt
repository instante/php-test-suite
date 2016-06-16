<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Http\Request;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

SandboxTestBootstrap::prepareUnitTest();

/** @var RequestBuilder $rb */
$rb = require __DIR__ . '/RequestBuilder.create.inc';
$ar = $rb->buildApplicationRequest();

Assert::false($ar->getFiles()['fooFile']->isOk());
Assert::same(Request::POST, $ar->getMethod());
Assert::same('barQ', $ar->getParameter('fooQuery'));
Assert::same('barP', $ar->getPost('fooPost'));
Assert::same('Foo', $ar->getPresenterName());
