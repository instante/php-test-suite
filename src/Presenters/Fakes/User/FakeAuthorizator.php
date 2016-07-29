<?php

namespace Instante\Tests\Presenters\Fakes\User;

use Nette\Security\IAuthorizator;

class FakeAuthorizator implements IAuthorizator
{
    public $acl = [];

    public function isAllowed($role, $resource, $privilege)
    {
        return isset($this->acl[$role][$resource]) && in_array($privilege, $this->acl[$role][$resource], TRUE);
    }

    /**
     * @param string $role - if resource and privilege are NULL,
     * they are taken from dot-separated role: allow('role.resource.privilege')
     * @param string $resource
     * @param string $privilege
     */
    public function allow($role, $resource = NULL, $privilege = NULL)
    {
        if ($resource === NULL && $privilege === NULL) {
            list($role, $resource, $privilege) = explode('.', $role);
        }
        $this->acl[$role][$resource][] = $privilege;
    }
}
