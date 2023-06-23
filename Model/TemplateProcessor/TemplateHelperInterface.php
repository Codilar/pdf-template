<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Handlebars\Context;
use Handlebars\Template;

interface TemplateHelperInterface
{
    /**
     * @see https://github.com/zordius/lightncandy#custom-helper-examples
     *
     * @param Template $template
     * @param Context $context
     * @param $args
     * @param $source
     * @return mixed
     */
    public function execute(Template $template, Context $context, $args, $source);
}
