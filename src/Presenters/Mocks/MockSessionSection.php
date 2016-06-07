<?php

namespace Instante\Tests\Presenters\Mocks;

class MockSessionSection implements \IteratorAggregate, \ArrayAccess
{
    /** @var array */
    public $data = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function &__get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function offsetSet($name, $value)
    {
        $this->__set($name, $value);
    }

    public function offsetGet($name)
    {
        return $this->__get($name);
    }

    public function offsetExists($name)
    {
        return $this->__isset($name);
    }

    public function offsetUnset($name)
    {
        $this->__unset($name);
    }
}
