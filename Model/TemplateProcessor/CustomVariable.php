<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Handlebars\Context;
use Handlebars\Template;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use Magento\Variable\Model\Variable;

class CustomVariable implements TemplateHelperInterface
{
    private Variable $variable;

    /**
     * @param Variable $variable
     */
    public function __construct(
        Variable $variable
    )
    {
        $this->variable = $variable;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $code = $this->replaceQuotes($args);
        return $this->getVariable($code)->getValue(Variable::TYPE_TEXT);
    }

    /**
     * @param $code
     * @return Variable
     */
    protected function getVariable($code): Variable
    {
        return $this->variable->loadByCode($code);
    }

    protected function replaceQuotes(string $str)
    {
        return preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $str);
    }
}
