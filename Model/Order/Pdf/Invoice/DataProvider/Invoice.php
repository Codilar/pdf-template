<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf\Invoice\DataProvider;


use Codilar\PdfTemplate\Model\Order\Pdf\DataProviderInterface;

class Invoice implements DataProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getData(array $data = []): array
    {
        /** @var \Magento\Sales\Model\Order\Invoice $model */
        $model = $data['model'];
        return $model->getData();
    }
}
