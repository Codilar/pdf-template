<?php


namespace Codilar\PdfTemplate\Model\Data\Mock;


use Codilar\PdfTemplate\Model\Order\Pdf\Shipment\DataProvider;

class Shipment
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * Invoice constructor.
     * @param \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $collectionFactory
     * @param DataProvider $dataProvider
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory  $collectionFactory,
        DataProvider $dataProvider
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param string $mockModelId
     * @return array
     */
    public function getData(string $mockModelId)
    {
        /** @var \Magento\Sales\Model\Order\Shipment $shipment */
        $shipment = $this->collectionFactory->create()
            ->addFieldToFilter('increment_id', $mockModelId)->getFirstItem();

        if (!$shipment->getId()) {
            return new AbstractMock();
        } else {
            $data = [
                'model' => $shipment
            ];
            return $this->dataProvider->getData($data);
        }
    }
}
