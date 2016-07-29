<?php

namespace Instante\Tests\Presenters\DI;

use Instante\Tests\Presenters\Fakes\FakePresenterFactory;
use Instante\Tests\Presenters\Fakes\FakeSession;
use Instante\Tests\Presenters\Fakes\FakeTemplateFactory;
use Instante\Tests\Presenters\Fakes\User\FakeAuthenticator;
use Instante\Tests\Presenters\Fakes\User\FakeAuthorizator;
use Instante\Tests\Presenters\Fakes\User\FakeUserStorage;
use Instante\Tests\Presenters\Request\RequestBuilder;
use Nette\Application\IPresenterFactory;
use Nette\Application\IRouter;
use Nette\Application\Request as AppRequest;
use Nette\Application\Routers\SimpleRouter;
use Nette\Application\UI\ITemplateFactory;
use Nette\Application\UI\Presenter;
use Nette\DI\Container;
use Nette\Http\IRequest as IHttpRequest;
use Nette\Http\IResponse as IHttpResponse;
use Nette\Http\Response as HttpResponse;
use Nette\Http\Session;
use Nette\Security\IAuthenticator;
use Nette\Security\IAuthorizator;
use Nette\Security\IUserStorage;
use Nette\Security\User;

class PrimaryDependencyContainer
{
    /** @var IRouter */
    private $router;

    /** @var Session */
    private $session;

    /** @var IUserStorage */
    private $userStorage;

    /** @var IAuthenticator */
    private $authenticator;

    /** @var IAuthorizator */
    private $authorizator;

    /** @var ITemplateFactory */
    private $templateFactory;

    /** @var IHttpResponse */
    private $httpResponse;

    /** @var IPresenterFactory */
    private $presenterFactory;

    /** @var RequestBuilder */
    private $requestBuilder;

    /** @var IHttpRequest */
    private $httpRequest;

    /** @var AppRequest */
    private $appRequest;

    /** @var Container - not faked by default */
    private $context;

    /** @var IHttpRequest */
    private $usedHttpRequest;

    /** @var AppRequest */
    private $usedAppRequest;

    /** @var User */
    private $user;

    public function __construct(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
        $this->httpResponse = new HttpResponse;
        $this->router = new SimpleRouter;
        $this->presenterFactory = new FakePresenterFactory;
        $this->session = new FakeSession;
        $this->userStorage = new FakeUserStorage;
        $this->authenticator = new FakeAuthenticator;
        $this->authorizator = new FakeAuthorizator;
        $this->templateFactory = new FakeTemplateFactory;
    }

    /**
     * Needs to be called before injectPrimary() - decides if to build http and app request or use explicitly specified
     * ones inside this container.
     */
    private function buildRequests()
    {
        $this->usedAppRequest = $this->appRequest !== NULL
            ? $this->appRequest : $this->requestBuilder->buildApplicationRequest();
        $this->usedHttpRequest = $this->httpRequest !== NULL
            ? $this->httpRequest : $this->requestBuilder->buildHttpRequest($this->usedAppRequest, $this->router);
    }


    public function injectTo(Presenter $presenter)
    {
        $this->buildRequests();
        $presenter->injectPrimary(
            $this->context,
            $this->presenterFactory,
            $this->router,
            $this->usedHttpRequest,
            $this->httpResponse,
            $this->session,
            $this->getUserService(),
            $this->templateFactory
        );
    }

    public function getUserService()
    {
        if ($this->user === NULL) {
            $this->user = new User($this->userStorage, $this->authenticator, $this->authorizator);
        }
        return $this->user;
    }

    /** @return IHttpRequest */
    public function getHttpRequest()
    {
        if ($this->httpRequest === NULL) {
            $this->httpRequest = $this->requestBuilder->buildHttpRequest($this->getAppRequest(), $this->getRouter());
        }
        return $this->httpRequest;
    }

    /** @return AppRequest */
    public function getAppRequest()
    {
        return $this->appRequest;
    }

    /**
     * @param AppRequest $appRequest
     * @return $this
     */
    public function setAppRequest(AppRequest $appRequest)
    {
        $this->appRequest = $appRequest;
        return $this;
    }

    /** @return IHttpResponse */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * @param IHttpResponse $httpResponse
     * @return $this
     */
    public function setHttpResponse(IHttpResponse $httpResponse)
    {
        $this->httpResponse = $httpResponse;
        return $this;
    }

    /** @return Container */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Container $context
     * @return $this
     */
    public function setContext(Container $context)
    {
        $this->context = $context;
        return $this;
    }

    /** @return IRouter */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param IRouter $router
     * @return $this
     */
    public function setRouter(IRouter $router)
    {
        $this->router = $router;
        return $this;
    }

    /** @return Session */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /** @return IUserStorage */
    public function getUserStorage()
    {
        return $this->userStorage;
    }

    /**
     * @param IUserStorage $userStorage
     * @return $this
     */
    public function setUserStorage(IUserStorage $userStorage)
    {
        $this->userStorage = $userStorage;
        return $this;
    }

    /** @return IAuthenticator */
    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    /**
     * @param IAuthenticator $authenticator
     * @return $this
     */
    public function setAuthenticator(IAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
        return $this;
    }

    /** @return IAuthorizator */
    public function getAuthorizator()
    {
        return $this->authorizator;
    }

    /**
     * @param IAuthorizator $authorizator
     * @return $this
     */
    public function setAuthorizator(IAuthorizator $authorizator)
    {
        $this->authorizator = $authorizator;
        return $this;
    }

    /** @return ITemplateFactory */
    public function getTemplateFactory()
    {
        return $this->templateFactory;
    }

    /**
     * @param ITemplateFactory $templateFactory
     * @return $this
     */
    public function setTemplateFactory(ITemplateFactory $templateFactory)
    {
        $this->templateFactory = $templateFactory;
        return $this;
    }

    /** @return IPresenterFactory */
    public function getPresenterFactory()
    {
        return $this->presenterFactory;
    }

    /**
     * @param IPresenterFactory $presenterFactory
     * @return $this
     */
    public function setPresenterFactory(IPresenterFactory $presenterFactory)
    {
        $this->presenterFactory = $presenterFactory;
        return $this;
    }

    /** @return AppRequest */
    public function getUsedAppRequest()
    {
        return $this->usedAppRequest;
    }

    /** @return IHttpRequest */
    public function getUsedHttpRequest()
    {
        return $this->usedHttpRequest;
    }
}
