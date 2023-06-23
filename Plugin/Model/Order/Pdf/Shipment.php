<?php


namespace Codilar\PdfTemplate\Plugin\Model\Order\Pdf;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order\Pdf\Shipment as Subject;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection;
use Magento\Store\Model\ScopeInterface;
use Codilar\PdfTemplate\Model\Order\Pdf\ShipmentFactory as AdvancedShipmentFactory;

class Shipment
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var AdvancedShipmentFactory
     */
    private $advancedShipmentFactory;

    /**
     * Invoice constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param AdvancedShipmentFactory $advancedShipmentFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        AdvancedShipmentFactory $advancedShipmentFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->advancedShipmentFactory = $advancedShipmentFactory;
    }

    /**
     * Return PDF document
     *
     * @param array|Collection $shipments
     * @return \Zend_Pdf
     */
    public function aroundGetPdf(Subject $subject, callable $proceed, $shipments = [])
    {
        if (count($shipments)) {
            /** @var \Magento\Sales\Model\Order\Invoice $shipments */
            $shipment = reset($shipments);
            if ($this->scopeConfig->getValue('sales_pdf/shipment/pdf_template', ScopeInterface::SCOPE_STORE, $shipment->getStoreId())) {
                return $this->advancedShipmentFactory->create([
                    'shipments' => $shipments
                ]);
            }
        }
        return $proceed($shipments);
    }
}
