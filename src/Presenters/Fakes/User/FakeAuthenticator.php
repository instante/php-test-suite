<?php

namespace Instante\Tests\Presenters\Fakes\User;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;

/**
 * Trivial implementation taken from Nette SimpleAuthenticator, lists made mutable for easier testing purposes
 */
class FakeAuthenticator implements IAuthenticator
{
    /** @var array list of pairs username => [password, identity] | password */
    public $userList;

    /** @var array list of pairs username => role[] */
    public $usersRoles;


    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        foreach ($this->userList as $name => $pass) {
            if (strcasecmp($name, $username) === 0) {
                if (is_array($pass)) {
                    $identity = $pass[1];
                    $pass = $pass[0];
                } else {
                    $identity = FALSE;
                }
                if ((string)$pass === (string)$password) {
                    return $identity !== FALSE
                        ? $identity
                        : new Identity($name, isset($this->usersRoles[$name]) ? $this->usersRoles[$name] : NULL);
                } else {
                    throw new AuthenticationException('Invalid password.', self::INVALID_CREDENTIAL);
                }
            }
        }
        throw new AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
    }

}
