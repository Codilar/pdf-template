<?php


namespace Codilar\PdfTemplate\Controller\Adminhtml\Shipment;


use Magento\Backend\App\Action;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Codilar\PdfTemplate\Model\TemplateProcessor;
use Codilar\PdfTemplate\Model\Data\Mock\Shipment as ShipmentMock;

class Render extends Action implements CsrfAwareActionInterface
{
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;
    /**
     * @var ShipmentMock
     */
    private $shipmentMock;

    /**
     * Render constructor.
     * @param Action\Context $context
     * @param TemplateProcessor $templateProcessor
     * @param ShipmentMock $shipmentMock
     */
    public function __construct(
        Action\Context $context,
        TemplateProcessor $templateProcessor,
        ShipmentMock $shipmentMock
    )
    {
        parent::__construct($context);
        $this->templateProcessor = $templateProcessor;
        $this->shipmentMock = $shipmentMock;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $rawHtml = $this->getRequest()->getParam('raw');
        $mockData = $this->shipmentMock->getData();
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
