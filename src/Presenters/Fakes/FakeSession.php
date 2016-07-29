<?php

namespace Instante\Tests\Presenters\Fakes;

use /** @noinspection PhpDeprecationInspection */
    Nette\Http\ISessionStorage;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\NotImplementedException;
use SessionHandlerInterface;

// TODO: create FakeGenerator and base this on it
class FakeSession extends Session
{
    /** @var array */
    public $sections = [];

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
    }

    public function getSection($section, $class = SessionSection::class)
    {
        if (!isset($this->sections[$section])) {
            $this->sections[$section] = new FakeSessionSection;
        }
        return $this->sections[$section];
    }

    public function hasSection($section)
    {
        return isset($this->sections[$section]);
    }

    private static function notInFake()
    {
        throw new NotImplementedException('This fake implementation supports only getSection() and hasSection() methods.');
    }

    public function start()
    {
        self::notInFake();
    }

    public function isStarted()
    {
        self::notInFake();
    }

    public function close()
    {
        self::notInFake();
    }

    public function destroy()
    {
        self::notInFake();
    }

    public function exists()
    {
        self::notInFake();
    }

    public function regenerateId()
    {
        self::notInFake();
    }

    public function getId()
    {
        self::notInFake();
    }

    public function setName($name)
    {
        self::notInFake();
    }

    public function getName()
    {
        self::notInFake();
    }

    public function getIterator()
    {
        self::notInFake();
    }

    public function clean()
    {
        self::notInFake();
    }

    public function setOptions(array $options)
    {
        self::notInFake();
    }

    public function getOptions()
    {
        self::notInFake();
    }

    public function setExpiration($time)
    {
        self::notInFake();
    }

    public function setCookieParameters($path, $domain = NULL, $secure = NULL)
    {
        self::notInFake();
    }

    public function getCookieParameters()
    {
        self::notInFake();
    }

    public function setSavePath($path)
    {
        self::notInFake();
    }

    public function setStorage(
        /** @noinspection PhpDeprecationInspection */
        ISessionStorage $storage
    ) {
        self::notInFake();
    }

    public function setHandler(SessionHandlerInterface $handler)
    {
        self::notInFake();
    }

}
