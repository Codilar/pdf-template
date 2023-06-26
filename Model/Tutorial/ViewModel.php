<?php

namespace Codilar\PdfTemplate\Model\Tutorial;

use Codilar\PdfTemplate\Model\TemplateProcessor;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ViewModel implements ArgumentInterface
{
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;

    /**
     * @param TemplateProcessor $templateProcessor
     */
    public function __construct(
        TemplateProcessor $templateProcessor
    )
    {
        $this->templateProcessor = $templateProcessor;
    }

    public function getTemplateHelpers(): array
    {
        return array_keys($this->templateProcessor->getTemplateHelpers());
    }
}
