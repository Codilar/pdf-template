<?php

namespace Codilar\PdfTemplate\Block\Adminhtml;

use Codilar\PdfTemplate\Model\TemplateProcessor;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Tutorial extends \Magento\Backend\Block\Template
{
    private TemplateProcessor $templateProcessor;

    /**
     * @param Context $context
     * @param TemplateProcessor $templateProcessor
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        TemplateProcessor $templateProcessor,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->templateProcessor = $templateProcessor;
    }

    public function getTemplateHelpers(): array
    {
        return array_keys($this->templateProcessor->getTemplateHelpers());
    }
}
