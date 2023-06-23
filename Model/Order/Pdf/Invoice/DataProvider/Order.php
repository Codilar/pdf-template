<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf\Invoice\DataProvider;


use Codilar\PdfTemplate\Model\Order\Pdf\DataProviderInterface;

class Order implements DataProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getData(array $data = []): array
    {
        /** @var \Magento\Sales\Model\Order\Invoice $model */
        $model = $data['model'];
        return $model->getOrder()->getData();
    }
}
