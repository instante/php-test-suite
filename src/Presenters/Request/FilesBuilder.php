<?php

namespace Instante\Tests\Presenters\Request;

use Nette\FileNotFoundException;
use Nette\Http\FileUpload;
use Nette\InvalidStateException;

class FilesBuilder
{

    /** @var array */
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
     * @param string $key
     * @param string $name original file name
     * @param string|NULL $tmpName path to tmp copy of the file; $name is copied to a temp file if tmpName is NULL
     * @param int $error upload error
     * @return $this
     */
    public function addFileUpload($key, $name, $tmpName = NULL, $error = UPLOAD_ERR_OK)
    {
        $this->addFiles([$key => $this->createFileUpload($name, $tmpName, $error)]);
        return $this;
    }

    /**
     * Adds fake file that failed to upload
     * @param string $key
     * @param int $error upload error
     * @return $this
     */
    public function addFailedFileUpload($key, $error = UPLOAD_ERR_NO_FILE)
    {
        $this->addFiles([$key => $this->createFileUpload(NULL, NULL, $error)]);
        return $this;
    }

    /**
     * @param string $name original path to the file
     * @param string|NULL $tmpName path to tmp copy of the file; $name is copied to a temp file if tmpName is NULL
     * @param int $error upload error
     * @return FileUpload
     * @throws FileNotFoundException
     * @throws InvalidStateException
     */
    private function createFileUpload($name, $tmpName = NULL, $error = UPLOAD_ERR_OK)
    {
        if ($error === UPLOAD_ERR_OK) {
            if (!file_exists($tmpName ?: $name)) {
                throw new FileNotFoundException('File passed to ' . __METHOD__
                    . ' has to exist when error is UPLOAD_ERR_OK');
            }
            if ($tmpName === NULL) {
                if ($this->uploadTempDir === NULL) {
                    throw new InvalidStateException('Temp dir for uploads was not configured, cannot test uploads');
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
                throw new InvalidStateException('file uploads expects nested array of strings or FileUploads, '
                    . (is_object($file) ? get_class($file) : gettype($file)) . ' given.');
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
