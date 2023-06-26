<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;
use Handlebars\Template;

class Render implements TemplateHelperInterface, RendererInterface
{
    /**
     * @var RendererInterface[]
     */
    private $renderers;

    /**
     * @param RendererInterface[] $renderers
     */
    public function __construct(
        array $renderers = []
    )
    {
        $this->renderers = $renderers;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        return $this->render($args, $context);
    }

    /**
     * @inheirtDoc
     */
    public function render(string $value, Context $context, $graceful = true)
    {
        $rendererRegex = '/\$(.+?)\((.*?)\)/';
        if (preg_match($rendererRegex, $value, $matches)) {
            $rendererType = $matches[1];
            $value = $this->replaceQuotes($matches[2]);
            $renderer = $this->renderers[$rendererType] ?? null;
            if ($renderer) {
                return $renderer->render($value, $context);
            }
        }
        if ($context->get($value)) {
            return $context->get($value);
        }
        if ($graceful) {
            return $value;
        } else {
            return '';
        }
    }

    protected function replaceQuotes(string $str): string
    {
        return (string)preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $str);
    }
}
