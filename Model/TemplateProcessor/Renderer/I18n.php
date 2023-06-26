<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor\Renderer;

use Codilar\PdfTemplate\Model\TemplateProcessor\RendererInterface;
use Handlebars\Context;

class I18n implements RendererInterface
{

    public function render(string $value, Context $context): string
    {
        return __($value);
    }
}
