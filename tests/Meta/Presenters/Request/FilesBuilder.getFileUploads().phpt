<?php
namespace Instante\Tests\Meta\Presenters\Request;

use Instante\Tests\Presenters\Request\FilesBuilder;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Nette\Http\FileUpload;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

$fb = new FilesBuilder(SandboxTestBootstrap::$tempDir . '/uploads');

$fb->setFiles([
    'foo' => new FileUpload([
        'name' => 'foo',
        'tmp_name' => __DIR__ . '/existingFile',
        'size' => 10,
        'type' => 'text/plain',
        'error' => UPLOAD_ERR_OK,
    ]),
    'bar' => __DIR__ . '/existingFile',
    'baz' => [
        'foo1' => new FileUpload([
            'error' => UPLOAD_ERR_NO_FILE,
        ]),
        'bar1' => __DIR__ . '/existingFile2',
    ],
]);

$u = $fb->getFileUploads();

Assert::count(3, $u);
Assert::type(FileUpload::class, $u['foo']);
Assert::same('foo', $u['foo']->getName());
Assert::same('text/plain', $u['foo']->getContentType());
Assert::same(10, $u['foo']->getSize());
Assert::true($u['foo']->isOk());

Assert::type(FileUpload::class, $u['bar']);
Assert::same('existingFile', $u['bar']->getName());
Assert::same(filesize(__DIR__ . '/existingFile'), $u['bar']->getSize());
Assert::same(file_get_contents(__DIR__ . '/existingFile'), file_get_contents($u['bar']->getTemporaryFile()));

Assert::type('array', $u['baz']);
Assert::false($u['baz']['foo1']->isOk());
Assert::same(file_get_contents(__DIR__ . '/existingFile2'), file_get_contents($u['baz']['bar1']->getTemporaryFile()));

