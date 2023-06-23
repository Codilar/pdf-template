<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf\Shipment;


use Codilar\PdfTemplate\Model\Order\Pdf\DataProviderInterface;

class DataProvider implements DataProviderInterface
{
    /**
     * @var DataProviderInterface[]
     */
    private $providers;

    /**
     * DataProvider constructor.
     * @param array $providers
     */
    public function __construct(
        array $providers = []
    )
    {
        $this->providers = $providers;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getData(array $data = []): array
    {
        foreach ($this->providers as $key => $provider) {
            $data[$key] = $provider->getData($data);
        }
        return $data;
    }
}
