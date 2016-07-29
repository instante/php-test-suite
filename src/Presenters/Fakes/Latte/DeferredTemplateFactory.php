<?php

namespace Instante\Tests\Presenters\Fakes\Latte;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;

class DeferredTemplateFactory implements ITemplateFactory
{
    /** @var TemplateFactory */
    private $templateFactory;

    /** @var callable */
    private $templateFactoryFactory;

    /**
     * DeferredTemplateFactory constructor.
     * @param callable $templateFactoryFactory
     */
    public function __construct(callable $templateFactoryFactory)
    {
        $this->templateFactoryFactory = $templateFactoryFactory;
    }

    private function getTemplateFactory()
    {
        if ($this->templateFactory === NULL) {
            $this->templateFactory = call_user_func($this->templateFactoryFactory);
        }
        return $this->templateFactory;
    }


    /**
     * @param Control $control
     * @return ITemplate
     */
    function createTemplate(Control $control = NULL)
    {
        return $this->getTemplateFactory()->createTemplate($control);
    }
}
