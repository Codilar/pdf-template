<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Handlebars\Context;
use Handlebars\Template;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use Magento\Variable\Model\Variable;

class CustomVariableHTML extends CustomVariable implements TemplateHelperInterface
{

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $code = $this->replaceQuotes($args);
        return $this->getVariable($code)->getValue(Variable::TYPE_HTML);
    }
}
