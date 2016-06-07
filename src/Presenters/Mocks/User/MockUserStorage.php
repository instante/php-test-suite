<?php

namespace Instante\Tests\Presenters\Mocks\User;

use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

class MockUserStorage implements IUserStorage
{
    /** @var bool */
    private $authenticated;

    /** @var IIdentity */
    private $identity;

    /** @var int enum of IUserStorage::{MANUAL,INACTIVITY,BROWSER_CLOSED} */
    private $logoutReason;

    public function setAuthenticated($state)
    {
        $this->authenticated = $state;
    }

    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    public function setIdentity(IIdentity $identity = NULL)
    {
        $this->identity = $identity;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setExpiration($time, $flags = 0)
    {
        //do nothing
    }

    /** @return int */
    public function getLogoutReason()
    {
        return $this->logoutReason;
    }

    /**
     * @param int $logoutReason
     * @return $this
     */
    public function setLogoutReason($logoutReason)
    {
        $this->logoutReason = $logoutReason;
        return $this;
    }
}
