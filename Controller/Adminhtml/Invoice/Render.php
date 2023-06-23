<?php


namespace Codilar\PdfTemplate\Controller\Adminhtml\Invoice;


use Magento\Backend\App\Action;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Codilar\PdfTemplate\Model\TemplateProcessor;
use Codilar\PdfTemplate\Model\Data\Mock\Invoice as InvoiceMock;

class Render extends Action implements CsrfAwareActionInterface
{
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;
    /**
     * @var InvoiceMock
     */
    private $invoiceMock;

    /**
     * Render constructor.
     * @param Action\Context $context
     * @param TemplateProcessor $templateProcessor
     * @param InvoiceMock $invoiceMock
     */
    public function __construct(
        Action\Context $context,
        TemplateProcessor $templateProcessor,
        InvoiceMock $invoiceMock
    )
    {
        parent::__construct($context);
        $this->templateProcessor = $templateProcessor;
        $this->invoiceMock = $invoiceMock;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $rawHtml = $this->getRequest()->getParam('raw');
        $mockData = $this->invoiceMock->getData();
        $response->setData([
            'rendered_html' => $this->templateProcessor->render($rawHtml, $mockData)
        ]);
        return $response;
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
