<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\FilesBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\FileNotFoundException;
use Nette\Http\FileUpload;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

SandboxTestBootstrap::prepareUnitTest();

$fb = new FilesBuilder(SandboxTestBootstrap::$tempDir . '/uploads');

$fb->addFileUpload('foo', 'fu.txt', $tmpName = __DIR__ . '/existingFile');

/** @var FileUpload $u1 */
$u1 = $fb->getFiles()['foo'];
Assert::type(FileUpload::class, $u1);
Assert::same('fu.txt', $u1->getName());
Assert::same($tmpName, $u1->getTemporaryFile());
Assert::same(UPLOAD_ERR_OK, $u1->getError());

$fb->addFileUpload(['foo', 'bar', 'baz'], NULL, NULL, UPLOAD_ERR_NO_FILE);

/** @var FileUpload $u2 */
$u2 = $fb->getFiles()['foo']['bar']['baz'];
Assert::type(FileUpload::class, $u2);
Assert::null($u2->getName());
Assert::null($u2->getTemporaryFile());
Assert::same(UPLOAD_ERR_NO_FILE, $u2->getError());

Assert::exception(function () use ($fb) {
    $fb->addFileUpload('foo', 'fu.txt', __DIR__ . '/notExistingFile');
}, FileNotFoundException::class);
