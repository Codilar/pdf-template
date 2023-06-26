<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;

interface RendererInterface
{
    /**
     * @param string $value
     * @param Context $context
     * @return mixed
     */
    public function render(string $value, Context $context);
}
