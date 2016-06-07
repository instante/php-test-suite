<?php

namespace Instante\Tests\Presenters\Mocks;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplateFactory;

/**
 * Creates simple template that is not processed in any way, just passes through its source
 */
class MockTemplateFactory implements ITemplateFactory
{
    function createTemplate(Control $control = NULL)
    {
        return new MockTemplate;
    }
}
