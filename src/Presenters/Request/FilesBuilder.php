<?php

namespace Instante\Tests\Presenters\Request;

use Instante\Tests\Presenters\TempDirNotSpecifiedException;
use Nette\FileNotFoundException;
use Nette\Http\FileUpload;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

class FilesBuilder
{

    /** @var array values can be one of \Nette\Http\FileUpload, string local file path or nested array */
    private $files = [];

    /** @var string */
    private $uploadTempDir;

    /** @param string $uploadTempDir */
    public function __construct($uploadTempDir)
    {
        $this->uploadTempDir = $uploadTempDir;
    }


    /**
     * Adds fake uploaded file
     *
     * @param array|string $key - array key is used to nest files, like ['foo', 'bar'] for files['foo']['bar']
     * @param string $name original file name
     * @param string $tmpName path to tmp copy of the file; $name is copied to a temp file if tmpName is NULL
     * @param int $error upload error
     * @return $this
     *
     * @throws FileNotFoundException
     * @throws TempDirNotSpecifiedException
     */
    public function addFileUpload($key, $name, $tmpName = '', $error = UPLOAD_ERR_OK)
    {
        $upload = $this->createFileUpload($name, $tmpName, $error);
        $this->insertUploadToKey($key, $upload);
        return $this;
    }

    /**
     * Adds fake file that failed to upload
     * @param array|string $key - array key is used to nest files, like ['foo', 'bar'] for files['foo']['bar']
     * @param int $error upload error
     * @return $this
     * @throws FileNotFoundException
     * @throws TempDirNotSpecifiedException
     */
    public function addFailedFileUpload($key, $error = UPLOAD_ERR_NO_FILE)
    {
        $upload = $this->createFileUpload('', '', $error);
        $this->insertUploadToKey($key, $upload);
        return $this;
    }

    /**
     * @param array|string $key
     * @param FileUpload $upload
     */
    private function insertUploadToKey($key, FileUpload $upload)
    {
        if (!is_array($key)) {
            $key = [$key];
        }
        if (count($key) === 0) {
            throw new InvalidArgumentException('empty key');
        }
        $toAdd = $upload;
        foreach (array_reverse($key) as $k) {
            $toAdd = [$k => $toAdd];;
        }
        $this->addFiles($toAdd);
    }

    /**
     * @param string $name original path to the file
     * @param string $tmpName path to tmp copy of the file; $name is copied to a temp file if tmpName is NULL
     * @param int $error upload error
     * @return FileUpload
     * @throws FileNotFoundException
     * @throws TempDirNotSpecifiedException
     */
    private function createFileUpload($name, $tmpName = '', $error = UPLOAD_ERR_OK)
    {
        if ($error === UPLOAD_ERR_OK) {
            if (!file_exists($tmpName ?: $name)) {
                throw new FileNotFoundException('File passed to ' . __METHOD__
                    . ' has to exist when error is UPLOAD_ERR_OK');
            }
            if ($tmpName === '') {
                if ($this->uploadTempDir === NULL) {
                    throw new TempDirNotSpecifiedException('Temp dir for uploads was not configured');
                }
                $tmpName = tempnam($this->uploadTempDir, 'InstanteTestUpload');
                copy($name, $tmpName);
            }
        }
        return new FileUpload([
            'name' => basename($name),
            'size' => $error === UPLOAD_ERR_OK && is_file($tmpName) ? filesize($tmpName) : 0,
            'type' => 0, // dummy value as FileUpload only checks its presence :)
            'tmp_name' => $tmpName,
            'error' => $error,
        ]);
    }

    /**
     * Returns processed collection for http/app request
     *
     * @return FileUpload[]
     * @throws FileNotFoundException
     * @throws TempDirNotSpecifiedException
     */
    public function getFileUploads()
    {
        $files = func_num_args() ? func_get_arg(0) : $this->files; //hidden argument for recursive calls
        $out = [];
        foreach ($files as $key => $file) {
            if (is_array($file)) {
                $out[$key] = $this->getFileUploads($file);
            } elseif (is_string($file)) {
                $out[$key] = $this->createFileUpload($file);
            } elseif ($file instanceof FileUpload) {
                $out[$key] = $file;
            } else {
                throw new InvalidStateException(sprintf(
                    'file uploads expects nested array of strings or FileUploads, %s found in %s::$files',
                    is_object($file) ? get_class($file) : gettype($file),
                    __CLASS__
                ));
            }
        }
        return $out;
    }

    /**
     * Returns current raw definitions
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Replaces raw upload definitions
     *
     * @param array $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Adds multiple file upload definitions
     *
     * @param array $files : values can be one of \Nette\Http\FileUpload, string local file path or nested array
     * @return $this
     */
    public function addFiles($files)
    {
        $this->files = $files + $this->files;
        return $this;
    }
}
