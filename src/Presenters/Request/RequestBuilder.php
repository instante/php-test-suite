<?php

namespace Instante\Tests\Presenters\Request;

use Nette\Application\IRouter;
use Nette\Application\Request as AppRequest;
use Nette\Http\Request as HttpRequest;
use Nette\Http\Url;
use Nette\Http\UrlScript;

class RequestBuilder
{
    /** @var string */
    private $presenterName;

    /** @var string */
    private $method = 'GET';

    /** @var array */
    private $query = [];

    /** @var array */
    private $post = [];

    /** @var array */
    private $appRequestFlags = [];

    /** @var FilesBuilder */
    private $filesBuilder;

    /** @var array */
    private $cookies = [];

    /** @var array */
    private $headers = [];

    /** @var string */
    private $remoteAddress = '127.0.0.1';

    /** @var string */
    private $remoteHost = 'localhost';

    /** @var callable|NULL */
    private $rawBodyCallback = NULL;

    /**
     * @param string $presenterName
     * @param $uploadTempDir
     */
    public function __construct($presenterName, $uploadTempDir)
    {
        $this->presenterName = $presenterName;
        if ($uploadTempDir === NULL) {
            trigger_error('Temporary directory for uploaded files was not specified. Testing uploads will not be available.', E_USER_NOTICE);
        } else {
            $this->filesBuilder = new FilesBuilder($uploadTempDir);
        }
    }

    public function buildApplicationRequest()
    {
        return new AppRequest(
            $this->presenterName,
            $this->method,
            $this->query,
            $this->post,
            $this->filesBuilder->getFileUploads(),
            array_combine($this->appRequestFlags, array_fill(0, count($this->appRequestFlags), TRUE))
        );
    }

    public function buildHttpRequest(AppRequest $appRequest, IRouter $router)
    {
        return new HttpRequest(
            new UrlScript($router->constructUrl($appRequest, new Url('http://instante.test/'))),
            NULL, //deprecated query parameter
            $this->post,
            $this->filesBuilder->getFileUploads(),
            $this->cookies,
            $this->headers,
            $this->method,
            $this->remoteAddress,
            $this->remoteHost,
            $this->rawBodyCallback
        );
    }

    //<editor-fold desc="getters and setters">

    /** @return array */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function addQuery($query)
    {
        $this->query = $query + $this->query;
        return $this;
    }

    /** @return array */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param array $post
     * @return $this
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @param array
     * @return $this
     */
    public function addPost($post)
    {
        $this->post = $post + $this->post;
        return $this;
    }

    /** @return array */
    public function getAppRequestFlags()
    {
        return $this->appRequestFlags;
    }

    /**
     * @param array $appRequestFlags
     * @return $this
     */
    public function setAppRequestFlags(array $appRequestFlags)
    {
        $this->appRequestFlags = $appRequestFlags;
        return $this;
    }

    /**
     * @param array|string $appRequestFlags
     * @return $this
     */
    public function addAppRequestFlags($appRequestFlags)
    {
        $this->appRequestFlags = $this->arrayize($appRequestFlags) + $this->appRequestFlags;
        return $this;
    }

    /** @return array */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     * @return $this
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * @param array $cookies
     * @return $this
     */
    public function addCookies($cookies)
    {
        $this->cookies = $cookies + $this->cookies;
        return $this;
    }

    /** @return string */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /** @return array */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function addHeaders($headers)
    {
        $this->headers = $headers + $this->headers;
        return $this;
    }

    /** @return string */
    public function getRemoteAddress()
    {
        return $this->remoteAddress;
    }

    /**
     * @param string $remoteAddress
     * @return $this
     */
    public function setRemoteAddress($remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
        return $this;
    }

    /** @return string */
    public function getRemoteHost()
    {
        return $this->remoteHost;
    }

    /**
     * @param string $remoteHost
     * @return $this
     */
    public function setRemoteHost($remoteHost)
    {
        $this->remoteHost = $remoteHost;
        return $this;
    }

    /** @return callable|NULL */
    public function getRawBodyCallback()
    {
        return $this->rawBodyCallback;
    }

    /**
     * @param callable|NULL $rawBodyCallback
     * @return $this
     */
    public function setRawBodyCallback(callable $rawBodyCallback = NULL)
    {
        $this->rawBodyCallback = $rawBodyCallback;
        return $this;
    }

    /** @return FilesBuilder */
    public function getFilesBuilder()
    {
        return $this->filesBuilder;
    }

    //</editor-fold>
    private function arrayize($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        return $value;
    }
}
