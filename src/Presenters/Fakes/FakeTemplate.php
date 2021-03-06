<?php

namespace Instante\Tests\Presenters\Fakes;

use Nette\Application\UI\ITemplate;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

class FakeTemplate implements ITemplate
{
    /** @var string */
    private $file;

    /** @var array */
    private $params = [];

    /** @var array */
    private $called = [];

    public function renderToString()
    {
        if ($this->file === NULL) {
            throw new InvalidStateException('Please set a template file first');
        }
        return file_get_contents($this->file);
    }

    public function render()
    {
        echo $this->renderToString();
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function __call($name, $args)
    {
        array_push($this->called, [$name, $args]);
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function &__get($name)
    {
        if (!array_key_exists($name, $this->params)) {
            throw new InvalidArgumentException("The variable '$name' does not exist in template.");
        }

        return $this->params[$name];
    }

    public function __isset($name)
    {
        return isset($this->params[$name]);
    }

    public function __unset($name)
    {
        unset($this->params[$name]);
    }

    /** @return array */
    public function getCalledMethods()
    {
        return $this->called;
    }
}
