<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;
use Handlebars\Template;
use Handlebars\Helpers;
use Magento\Framework\Exception\LocalizedException;

class IfElse implements TemplateHelperInterface
{
    private Helpers $handlebarHelpers;
    private RendererInterface $renderer;

    /**
     * @param Helpers $handlebarHelpers
     * @param RendererInterface $renderer
     */
    public function __construct(
        Helpers $handlebarHelpers,
        RendererInterface $renderer
    )
    {
        $this->handlebarHelpers = $handlebarHelpers;
        $this->renderer = $renderer;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $value = $this->renderer->render($args, $context, false);
        return $this->handlebarHelpers->helperIf($template, $context, $value ? 'this': '', $source);
    }
}
