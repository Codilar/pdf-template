<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor\Renderer;

use Codilar\PdfTemplate\Model\TemplateProcessor\RendererInterface;
use Handlebars\Context;

class I18n implements RendererInterface
{
    /**
     * @inheirtDoc
     */
    public function render(string $value, Context $context)
    {
        return __($value);
    }
}
