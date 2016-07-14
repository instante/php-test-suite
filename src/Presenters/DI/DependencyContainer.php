<?php

namespace Instante\Tests\Presenters\DI;

use Nette\Application\UI\Presenter;
use Nette\DI\Extensions\InjectExtension;
use Nette\DI\Helpers;
use Nette\Reflection\ClassType;

class DependencyContainer
{
    /** @var array */
    private $dependencies = [];

    public function addDependencies(array $dependencies)
    {
        $this->dependencies = $dependencies + $this->dependencies;
        return $this;
    }

    public function injectTo(Presenter $presenter)
    {
        $this->injectByProperties($presenter);
        $this->injectByMethods($presenter);
    }

    private function injectByProperties(Presenter $presenter)
    {
        if (class_exists(InjectExtension::class)) {
            /** @noinspection PhpInternalEntityUsedInspection */
            $properties = InjectExtension::getInjectProperties(get_class($presenter));  // Nette 2.3+
        } else {
            $properties = Helpers::getInjectProperties(new ClassType($presenter));  // Nette 2.2
        }
        foreach ($properties as $property => $type) {
            if (isset($this->dependencies['@' . $property])) {

                $presenter->$property = $this->dependencies['@' . $property];
            } elseif (isset($this->dependencies[$property])) {
                $presenter->$property = $this->dependencies[$property];
            }
        }
    }

    /**
     * @param Presenter $presenter
     */
    private function injectByMethods(Presenter $presenter)
    {
        if (class_exists(InjectExtension::class)) {
            /** @noinspection PhpInternalEntityUsedInspection */
            $methods = InjectExtension::getInjectMethods($presenter);  // Nette 2.3+
        } else {
            $methods = [];
            foreach (get_class_methods($presenter) as $method) {
                if (substr($method, 0, 6) === 'inject') {
                    $methods[] = $method;
                }
            }
        }
        unset($methods['injectPrimary']);
        foreach (array_reverse($methods) as $method) {
            $injectName = lcfirst(substr($method, 6));
            if (isset($this->dependencies[$injectName])) {
                if (!is_array($this->dependencies[$injectName])) {
                    $this->dependencies[$injectName] = [$this->dependencies[$injectName]];
                }
                call_user_func_array([$presenter, $method], $this->dependencies[$injectName]);
            }
        }
    }
}
