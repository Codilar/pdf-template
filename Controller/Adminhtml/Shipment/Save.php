<?php


namespace Codilar\PdfTemplate\Controller\Adminhtml\Shipment;


use Magento\Backend\App\Action;
use Codilar\PdfTemplate\Model\TemplateProcessor;

class Save extends Action
{
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param TemplateProcessor $templateProcessor
     */
    public function __construct(
        Action\Context $context,
        TemplateProcessor $templateProcessor
    )
    {
        parent::__construct($context);
        $this->templateProcessor = $templateProcessor;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $template = $this->getRequest()->getParam('template');
        $store = $this->getRequest()->getParam('store');
        $this->templateProcessor->setTemplate(TemplateProcessor::TEMPLATE_TYPE_SHIPMENT, $template, $store);
        $this->messageManager->addSuccessMessage(__('Template saved successfully'));
        return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
    }
}
