<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf;


interface DataProviderInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function getData(array $data = []): array;
}
