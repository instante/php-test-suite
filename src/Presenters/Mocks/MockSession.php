<?php

namespace Instante\Tests\Presenters\Mocks;

use /** @noinspection PhpDeprecationInspection */
    Nette\Http\ISessionStorage;
use Nette\Http\Session;
use Nette\NotImplementedException;

class MockSession extends Session
{
    /** @var array */
    public $sections = [];

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
    }

    public function getSection($section, $class = 'Nette\Http\SessionSection')
    {
        if (!isset($this->sections[$section])) {
            $this->sections[$section] = new MockSessionSection;
        }
        return $this->sections[$section];
    }

    public function hasSection($section)
    {
        return isset($this->sections[$section]);
    }

    private static function notInMock()
    {
        throw new NotImplementedException('This simple mock supports only getSection() and hasSection() methods.');
    }

    public function start()
    {
        self::notInMock();
    }

    public function isStarted()
    {
        self::notInMock();
    }

    public function close()
    {
        self::notInMock();
    }

    public function destroy()
    {
        self::notInMock();
    }

    public function exists()
    {
        self::notInMock();
    }

    public function regenerateId()
    {
        self::notInMock();
    }

    public function getId()
    {
        self::notInMock();
    }

    public function setName($name)
    {
        self::notInMock();
    }

    public function getName()
    {
        self::notInMock();
    }

    public function getIterator()
    {
        self::notInMock();
    }

    public function clean()
    {
        self::notInMock();
    }

    public function setOptions(array $options)
    {
        self::notInMock();
    }

    public function getOptions()
    {
        self::notInMock();
    }

    public function setExpiration($time)
    {
        self::notInMock();
    }

    public function setCookieParameters($path, $domain = NULL, $secure = NULL)
    {
        self::notInMock();
    }

    public function getCookieParameters()
    {
        self::notInMock();
    }

    public function setSavePath($path)
    {
        self::notInMock();
    }

    public function setStorage(
        /** @noinspection PhpDeprecationInspection */
        ISessionStorage $storage
    ) {
        self::notInMock();
    }

    public function setHandler(\SessionHandlerInterface $handler)
    {
        self::notInMock();
    }

}
