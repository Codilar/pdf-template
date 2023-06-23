<?php


namespace Codilar\PdfTemplate\Model\Data\Mock;


use Codilar\PdfTemplate\Model\Order\Pdf\Invoice\DataProvider;

class Invoice
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * Invoice constructor.
     * @param \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $collectionFactory
     * @param DataProvider $dataProvider
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory  $collectionFactory,
        DataProvider $dataProvider
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return array
     */
    public function getData()
    {
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoice = $this->collectionFactory->create()->getFirstItem();

        if (!$invoice->getId()) {
            return new AbstractMock();
        } else {
            $data = [
                'model' => $invoice
            ];
            return $this->dataProvider->getData($data);
        }
    }
}
