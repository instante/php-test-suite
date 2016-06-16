<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Application\Routers\SimpleRouter;
use Nette\Http\Request;
use Nette\NotSupportedException;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

/** @var RequestBuilder $rb */
$rb = require __DIR__ . '/RequestBuilder.create.inc';
$hr = $rb->buildHttpRequest($rb->buildApplicationRequest(), new SimpleRouter);

Assert::false($hr->getFile('fooFile')->isOk());
Assert::same(Request::POST, $hr->getMethod());
Assert::same('barQ', $hr->getQuery('fooQuery'));
Assert::same('barP', $hr->getPost('fooPost'));
Assert::same('barC', $hr->getCookie('fooCookie'));
Assert::same('barH', $hr->getHeader('fooHeader'));
if (RequestBuilder::isSupportedRawBodyCallback()) {
    Assert::same('a', $hr->getRawBody());
}
Assert::same('remoteAddr', $hr->getRemoteAddress());
Assert::same('remoteHost', $hr->getRemoteHost());

if (!RequestBuilder::isSupportedRawBodyCallback()) {
    Assert::exception(function () use ($rb) {
        $rb->setRawBodyCallback(function () { });
    }, NotSupportedException::class);
}
