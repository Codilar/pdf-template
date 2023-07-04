<?php


namespace Codilar\PdfTemplate\Block\Adminhtml\Shipment;


use Magento\Framework\Exception\LocalizedException;
use Codilar\PdfTemplate\Model\TemplateProcessor;

class Template extends \Codilar\PdfTemplate\Block\Adminhtml\Invoice\Template
{

    public function getDefaultMockModelId(): string
    {
        return $this->_scopeConfig->getValue('sales_pdf/shipment/default_mock_model_id');
    }

    /**
     * @return string
     */
    public function getRawTemplateData(): string
    {
        return $this->templateProcessor->getTemplate(TemplateProcessor::TEMPLATE_TYPE_SHIPMENT, $this->getRequest()->getParam('store'));
    }
}
