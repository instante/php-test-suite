<?php

namespace Instante\Tests\Presenters;

use Instante\Tests\Presenters\DI\DependencyContainer;
use Instante\Tests\Presenters\DI\PrimaryDependencyContainer;
use Instante\Tests\Presenters\Helpers\PresenterNameAutoDetector;
use Instante\Tests\Presenters\Request\RequestBuilder;
use Nette\Application\UI\Presenter;
use Nette\InvalidStateException;

class PresenterTester
{
    /** @var  Presenter */
    private $presenterCreator;

    /** @var  string */
    private $presenterName;

    /** @var bool when instantiated presenter is passed to constructor, this prevents tester from running it twice. */
    private $presenterAlreadyUsed = FALSE;

    /** @var DependencyContainer */
    private $dependencyContainer;

    /** @var PrimaryDependencyContainer */
    private $primaryDependencyContainer;

    /** @var RequestBuilder */
    private $requestBuilder;

    /**
     * @param Presenter|string|callable $presenterCreator
     * @param string|null $uploadTempDir
     * @param string $presenterName fully qualified presenter name (:Module:Module:Presenter)
     */
    public function __construct($presenterCreator, $uploadTempDir = NULL, $presenterName = NULL)
    {
        assert($presenterCreator instanceof Presenter || is_string($presenterCreator)
            || is_callable($presenterCreator));
        $this->presenterCreator = $presenterCreator;
        $this->presenterName = $presenterName !== NULL
            ? $presenterName : $this->autoDetectName();

        $this->requestBuilder = new RequestBuilder($this->presenterName, $uploadTempDir);
        $this->dependencyContainer = new DependencyContainer;
        $this->primaryDependencyContainer = new PrimaryDependencyContainer($this->requestBuilder);
    }

    public function runPresenter()
    {
        $presenter = $this->createPresenter();

        $this->primaryDependencyContainer->injectTo($presenter);
        $this->dependencyContainer->injectTo($presenter);
        $presenter->autoCanonicalize = FALSE;

        $appResponse = $presenter->run($this->primaryDependencyContainer->getUsedAppRequest());
        ob_start();
        $appResponse->send(
            $this->primaryDependencyContainer->getUsedHttpRequest(),
            $this->primaryDependencyContainer->getHttpResponse()
        );
        $responseBody = ob_get_clean();
        return new TestResult($this->primaryDependencyContainer->getHttpResponse(), $appResponse, $responseBody);
    }

    protected function createPresenter()
    {
        $this->checkInstanceUsedOnce();
        if ($this->presenterCreator instanceof Presenter) {
            return $this->presenterCreator;
        } elseif (is_string($this->presenterCreator)) {
            return new $this->presenterCreator;
        } else {
            return call_user_func($this->presenterCreator);
        }
    }

    private function checkInstanceUsedOnce()
    {
        if ($this->presenterCreator instanceof Presenter) {
            if ($this->presenterAlreadyUsed) {
                throw new InvalidStateException('When passed instantiated presenter to ' . __CLASS__
                    . ', runPresenter() can be called only once');
            }
            $this->presenterAlreadyUsed = TRUE;
        }
    }

    /** @return DependencyContainer */
    public function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /** @return PrimaryDependencyContainer */
    public function getPrimaryDependencyContainer()
    {
        return $this->primaryDependencyContainer;
    }

    /** @return RequestBuilder */
    public function getRequestBuilder()
    {
        return $this->requestBuilder;
    }

    protected function autoDetectName()
    {
        return PresenterNameAutoDetector::autoDetect($this->presenterCreator);
    }
}
