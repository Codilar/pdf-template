<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf\Shipment\DataProvider;


use Codilar\PdfTemplate\Model\Order\Pdf\DataProviderInterface;

class Tracking implements DataProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getData(array $data = []): array
    {
        /** @var \Magento\Sales\Model\Order\Shipment $model */
        $model = $data['model'];
        return $model->getTracks();
    }
}
