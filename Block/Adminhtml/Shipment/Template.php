<?php


namespace Codilar\PdfTemplate\Block\Adminhtml\Shipment;


use Magento\Framework\Exception\LocalizedException;
use Codilar\PdfTemplate\Model\TemplateProcessor;

class Template extends \Codilar\PdfTemplate\Block\Adminhtml\Invoice\Template
{
    /**
     * @return string
     */
    public function getScopeControl(): string
    {
        try {
            return $this->getLayout()->createBlock(\Magento\Backend\Block\Store\Switcher::class, 'store_switcher')->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getSubmitUrl(): string
    {
        return $this->getUrl('*/*/save', ['store' => $this->getRequest()->getParam('store')]);
    }

    /**
     * @return string
     */
    public function getRawTemplateData(): string
    {
        return $this->templateProcessor->getTemplate(TemplateProcessor::TEMPLATE_TYPE_SHIPMENT, $this->getRequest()->getParam('store'));
    }
}
