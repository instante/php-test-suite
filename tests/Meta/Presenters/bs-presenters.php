<?php

use Nette\Application\UI\Presenter;
use Tester\Environment;

require __DIR__ . '/../../bootstrap.php';

if (!class_exists(Presenter::class)) {
    Environment::skip('Install nette/application to enable presenter tests.');
}
