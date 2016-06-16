<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\FilesBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\FileNotFoundException;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

SandboxTestBootstrap::prepareUnitTest();

$fb = new FilesBuilder(SandboxTestBootstrap::$tempDir . '/uploads');

Assert::exception(function () use ($fb) {
    $fb->setFiles([TRUE]);
    $fb->getFileUploads();
}, InvalidStateException::class, sprintf(
    '~^file uploads expects nested array of strings or FileUploads, boolean found in~',
    FilesBuilder::class
));

Assert::exception(function () use ($fb) {
    $fb->addFileUpload([], __DIR__ . '/existingFile');
    $fb->getFileUploads();
}, InvalidArgumentException::class, '~empty key~');

Assert::exception(function () use ($fb) {
    $fb->addFileUpload('a', 'b', __DIR__ . '/notExistingFile');
    $fb->getFileUploads();
}, FileNotFoundException::class, '~has to exist~');

Assert::exception(function () {
    $fb1 = new FilesBuilder(NULL);
    $fb1->addFileUpload('a', __DIR__ . '/existingFile');
    $fb1->getFileUploads();
}, InvalidStateException::class, '~^Temp dir for uploads was not configured~');
