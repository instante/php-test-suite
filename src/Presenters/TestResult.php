<?php

namespace Instante\Tests\Presenters;

use Nette\Http\IResponse as IHttpResponse;
use Nette\Application\IResponse as IAppResponse;

class TestResult
{
    /** @var IHttpResponse */
    private $httpResponse;

    /** @var string */
    private $responseBody;

    /** @var IAppResponse */
    private $appResponse;

    /**
     * @param IHttpResponse $httpResponse
     * @param IAppResponse $appResponse
     * @param string $responseBody
     */
    public function __construct(IHttpResponse $httpResponse, IAppResponse $appResponse, $responseBody)
    {
        $this->httpResponse = $httpResponse;
        $this->responseBody = $responseBody;
        $this->appResponse = $appResponse;
    }

    /** @return IHttpResponse */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /** @return string */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /** @return IAppResponse */
    public function getAppResponse()
    {
        return $this->appResponse;
    }
}
