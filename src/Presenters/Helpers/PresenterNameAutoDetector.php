<?php

namespace Instante\Tests\Presenters\Helpers;

use Nette\Application\UI\Presenter;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

class PresenterNameAutoDetector
{
    public static function autoDetect($presenterCreator)
    {
        if ($presenterCreator instanceof Presenter) {
            $className = get_class($presenterCreator);
        } elseif (is_string($presenterCreator)) {
            $className = $presenterCreator;
        } elseif (is_callable($presenterCreator)) {
            $className = get_class(call_user_func($presenterCreator));
        } else {
            throw new InvalidArgumentException('$presenterCreator must be instance of ' . Presenter::class
                . ', presenter class name or callable factory');
        }
        $name = ':';
        foreach (explode('\\', $className) as $nsPart) {
            if (substr($nsPart, -6) === 'Module') {
                $name .= substr($nsPart, 0, -6);
            }
        }
        $lastSlash = strrpos($className, '\\');
        $classBaseName = $lastSlash !== FALSE ? substr($className, $lastSlash + 1) : $className;
        if (substr($classBaseName, -9) === 'Presenter') {
            return $name . substr($classBaseName, 0, -9);
        } else {
            throw new InvalidStateException('Cannot autodetect presenter name, class is not named "XxxPresenter"');
        }
    }
}
