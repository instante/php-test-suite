<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

Assert::error(function () {
    new RequestBuilder('foo', NULL);
}, E_USER_NOTICE);
