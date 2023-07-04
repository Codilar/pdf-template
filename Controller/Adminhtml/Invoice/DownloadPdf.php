<?php

namespace Codilar\PdfTemplate\Controller\Adminhtml\Invoice;

use Magento\Backend\App\Action;
use Codilar\PdfTemplate\Model\Order\Pdf\InvoiceFactory as AdvancedInvoiceFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory as InvoiceCollectionFactory;

class DownloadPdf extends Action
{
    /**
     * @var AdvancedInvoiceFactory
     */
    private $advancedInvoiceFactory;
    /**
     * @var FileFactory
     */
    private $fileFactory;
    /**
     * @var InvoiceCollectionFactory
     */
    private $invoiceCollectionFactory;

    /**
     * @param Context $context
     * @param AdvancedInvoiceFactory $advancedInvoiceFactory
     * @param FileFactory $fileFactory
     * @param InvoiceCollectionFactory $invoiceCollectionFactory
     */
    public function __construct(
        Context $context,
        AdvancedInvoiceFactory $advancedInvoiceFactory,
        FileFactory $fileFactory,
        InvoiceCollectionFactory $invoiceCollectionFactory
    )
    {
        parent::__construct($context);
        $this->advancedInvoiceFactory = $advancedInvoiceFactory;
        $this->fileFactory = $fileFactory;
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $store = $this->getRequest()->getParam('store');
        $invoiceId = $this->getRequest()->getParam('id');
        try {
            /** @var \Magento\Sales\Model\Order\Invoice $invoice */
            $invoice = $this->invoiceCollectionFactory->create()->addFieldToFilter('increment_id', $invoiceId)->getFirstItem();
            if (!$invoice->getId()) {
                throw NoSuchEntityException::singleField('increment_id', $invoiceId);
            }
            if ($store) {
                $invoice->setStoreId($store);
            }
            $pdf = $this->advancedInvoiceFactory->create([
                'invoices' => [$invoice]
            ]);
            return $this->fileFactory->create(
                sprintf('invoice_%s.pdf', date('Y-m-d')),
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
