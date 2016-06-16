<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\FilesBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Http\FileUpload;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

SandboxTestBootstrap::prepareUnitTest();

$fb = new FilesBuilder(SandboxTestBootstrap::$tempDir . '/uploads');

$fb->addFailedFileUpload('foo', UPLOAD_ERR_CANT_WRITE);

/** @var FileUpload $u1 */
$u1 = $fb->getFiles()['foo'];
Assert::type(FileUpload::class, $u1);
Assert::same('', $u1->getName());
Assert::same('', $u1->getTemporaryFile());
Assert::same(UPLOAD_ERR_CANT_WRITE, $u1->getError());
