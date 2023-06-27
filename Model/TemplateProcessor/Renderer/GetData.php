<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor\Renderer;

use Codilar\PdfTemplate\Model\Data\Storage;
use Codilar\PdfTemplate\Model\TemplateProcessor\RendererInterface;
use Handlebars\Context;

class GetData implements RendererInterface
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param Storage $storage
     */
    public function __construct(
        Storage $storage
    )
    {
        $this->storage = $storage;
    }

    /**
     * @inheirtDoc
     */
    public function render(string $value, Context $context)
    {
        return $this->storage->getData($value);
    }
}
