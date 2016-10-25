<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Instante\Tests\Presenters\TempDirNotSpecifiedException;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

Assert::exception(function () {
    $builder = new RequestBuilder('foo', NULL);
    $builder->getFilesBuilder()->addFileUpload('foo', __FILE__);
}, TempDirNotSpecifiedException::class);
