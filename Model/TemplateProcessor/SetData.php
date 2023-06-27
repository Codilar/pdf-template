<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Codilar\PdfTemplate\Model\Data\Storage;
use Handlebars\Context;
use Handlebars\Template;
use Magento\Framework\Exception\LocalizedException;

class SetData implements TemplateHelperInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param RendererInterface $renderer
     * @param Storage $storage
     */
    public function __construct(
        RendererInterface $renderer,
        Storage $storage
    )
    {
        $this->renderer = $renderer;
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $args = explode(' ', $args);
        if (count($args) !== 2) {
            throw new LocalizedException(__('Invalid syntax for assign. It should be of the format {{#assign name value}}'));
        }
        list($name, $value) = $args;
        $this->storage->setData($name, $this->renderer->render($value, $context));
        return '';
    }
}
