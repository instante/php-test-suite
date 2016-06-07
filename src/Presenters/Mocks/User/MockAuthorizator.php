<?php

namespace Instante\Tests\Presenters\Mocks\User;

use Nette\Security\IAuthorizator;

class MockAuthorizator implements IAuthorizator
{
    public $acl = [];

    public function isAllowed($role, $resource, $privilege)
    {
        return isset($this->acl[$role][$resource][$privilege]);
    }
}
