<?php

namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\RequestBuilder;
use Instante\Tests\TestBootstrap;
use Nette\Application\Request;

$rb = new RequestBuilder('Foo', TestBootstrap::$tempDir);
$rb->addAppRequestFlags(Request::SECURED)
    ->setCookies(['fooCookie' => 'barC'])
    ->setHeaders(['fooHeader' => 'barH'])
    ->setMethod(\Nette\Http\Request::POST)
    ->setPost(['fooPost' => 'barP'])
    ->setQuery(['fooQuery' => 'barQ'])
    ->setRemoteAddress('remoteAddr')
    ->setRemoteHost('remoteHost')
    ->setRawBodyCallback(function () { return 'a'; });

$rb->getFilesBuilder()
    ->addFailedFileUpload('fooFile');

return $rb;
