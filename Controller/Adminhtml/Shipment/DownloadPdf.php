<?php

namespace Codilar\PdfTemplate\Controller\Adminhtml\Shipment;

use Magento\Backend\App\Action;
use Codilar\PdfTemplate\Model\Order\Pdf\ShipmentFactory as AdvancedShipmentFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;

class DownloadPdf extends Action
{
    /**
     * @var AdvancedShipmentFactory
     */
    private $advancedShipmentFactory;
    /**
     * @var FileFactory
     */
    private $fileFactory;
    /**
     * @var ShipmentCollectionFactory
     */
    private $shipmentCollectionFactory;

    /**
     * @param Context $context
     * @param AdvancedShipmentFactory $advancedShipmentFactory
     * @param FileFactory $fileFactory
     * @param ShipmentCollectionFactory $shipmentCollectionFactory
     */
    public function __construct(
        Context $context,
        AdvancedShipmentFactory $advancedShipmentFactory,
        FileFactory $fileFactory,
        ShipmentCollectionFactory $shipmentCollectionFactory
    )
    {
        parent::__construct($context);
        $this->advancedShipmentFactory = $advancedShipmentFactory;
        $this->fileFactory = $fileFactory;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $store = $this->getRequest()->getParam('store');
        $shipmentId = $this->getRequest()->getParam('id');
        try {
            /** @var \Magento\Sales\Model\Order\Shipment $shipment */
            $shipment = $this->shipmentCollectionFactory->create()->addFieldToFilter('increment_id', $shipmentId)->getFirstItem();
            if (!$shipment->getId()) {
                throw NoSuchEntityException::singleField('increment_id', $shipmentId);
            }
            if ($store) {
                $shipment->setStoreId($store);
            }
            $pdf = $this->advancedShipmentFactory->create([
                'shipments' => [$shipment]
            ]);
            return $this->fileFactory->create(
                sprintf('shipment_%s.pdf', date('Y-m-d')),
                [
                    'type' => 'string',
                    'value' => $pdf->render(),
                    'rm' => true
                ],
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
        }
    }
}
