<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;

interface RendererInterface
{
    public function render(string $value, Context $context): string;
}
