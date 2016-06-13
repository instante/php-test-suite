<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\TestBootstrap;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

TestBootstrap::prepareUnitTest();

Assert::error(function () {
    new RequestBuilder('foo', NULL);
}, E_USER_NOTICE);
