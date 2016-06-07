<?php

namespace Instante\Tests\Presenters\DI;

use Nette\Application\UI\Presenter;
use Nette\DI\Extensions\InjectExtension;

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
        /** @noinspection PhpInternalEntityUsedInspection */
        $properties = InjectExtension::getInjectProperties(get_class($presenter));
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
        /** @noinspection PhpInternalEntityUsedInspection */
        $methods = InjectExtension::getInjectMethods($presenter);
        unset($methods['injectPrimary']);
        foreach (array_reverse($methods) as $method) {
            $injectName = lcfirst(substr($method, 6));
            if (isset($this->dependencies[$injectName])) {
                if (!is_array($this->dependencies[$injectName])) {
                    $this->dependencies[$injectName] = [$this->dependencies[$injectName]];
                }
                call_user_func_array($presenter->$method, $this->dependencies[$injectName]);
            }
        }
    }
}
