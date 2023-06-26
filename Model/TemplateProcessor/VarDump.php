<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Symfony\Component\VarDumper\VarDumper;
use Handlebars\Context;
use Handlebars\Template;

class VarDump implements TemplateHelperInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer
    )
    {
        $this->renderer = $renderer;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        ob_start();
        $args = explode(' ', $args);
        foreach ($args as $arg) {
            $arg = $this->renderer->render($arg, $context);
            if (class_exists(VarDumper::class)) {
                VarDumper::dump($arg);
            } else {
                var_dump($arg);
            }
        }
        return ob_get_clean();
    }
}
