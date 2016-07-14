<?php

namespace Instante\Tests\Utils;

use Nette\Reflection\ClassType;

/**
 * This class is used to easily access protected and private members of an object
 * without a need to write extensive code using reflection.
 * Please use with caution - breaking encapsulation is a bad idea even in tests,
 * if you feel you have to break encapsulation, twice consider refactoring your code first.
 */
class Expose
{
    private $obj;
    private $refl;

    /**
     * Expose constructor.
     * @param $obj
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
        $this->refl = new ClassType($obj);
    }

    /**
     * Call method with any visibility
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     */
    function __call($name, $arguments)
    {
        if ($this->refl->hasMethod($name)) {
            $m = $this->refl->getMethod($name);
            $m->setAccessible(TRUE);
            return $m->invokeArgs($this->obj, $arguments);
        } else {
            return call_user_func_array([$this->obj, $name], $arguments);
        }
    }

    /**
     * is utilized for reading data from any member
     *
     * @param $name string
     * @return mixed
     */
    function __get($name)
    {
        if ($this->refl->hasProperty($name)) {
            $p = $this->refl->getProperty($name);
            $p->setAccessible(TRUE);
            return $p->getValue($this->obj);
        } else {
            return $this->obj->$name;
        }
    }

    /**
     * write data to any property
     *
     * @param $name string
     * @param $value mixed
     * @return void
     */
    function __set($name, $value)
    {
        if ($this->refl->hasProperty($name)) {
            $p = $this->refl->getProperty($name);
            $p->setAccessible(TRUE);
            $p->setValue($this->obj, $value);
        } else {
            $this->obj->$name = $value;
        }
    }

    /**
     * is triggered by calling isset() or empty() on inaccessible members.
     *
     * @param $name string
     * @return bool
     */
    function __isset($name)
    {
        return $this->refl->hasProperty($name) ? TRUE : isset($this->obj->$name);
    }


}
