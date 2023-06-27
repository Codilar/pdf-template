<?php


namespace Codilar\PdfTemplate\Block\Adminhtml\Invoice;


use Magento\Backend\Block\Template\Context as Context;
use Magento\Framework\Exception\LocalizedException;
use Codilar\PdfTemplate\Model\TemplateProcessor;

class Template extends \Magento\Backend\Block\Template
{
    /**
     * @var TemplateProcessor
     */
    protected $templateProcessor;

    /**
     * Template constructor.
     * @param Context $context
     * @param TemplateProcessor $templateProcessor
     * @param array $data
     */
    public function __construct(
        Context $context,
        TemplateProcessor $templateProcessor,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->templateProcessor = $templateProcessor;
    }

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

    public function getDefaultMockModelId(): string
    {
        return '000000001';
    }

    /**
     * @return string
     */
    public function getRawTemplateData(): string
    {
        return $this->templateProcessor->getTemplate(TemplateProcessor::TEMPLATE_TYPE_INVOICE, $this->getRequest()->getParam('store'));
    }
}
