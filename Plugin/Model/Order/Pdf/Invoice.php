<?php


namespace Codilar\PdfTemplate\Plugin\Model\Order\Pdf;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order\Pdf\Invoice as Subject;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection;
use Magento\Store\Model\ScopeInterface;
use Codilar\PdfTemplate\Model\Order\Pdf\InvoiceFactory as AdvancedInvoiceFactory;

class Invoice
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var AdvancedInvoiceFactory
     */
    private $advancedInvoiceFactory;

    /**
     * Invoice constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param AdvancedInvoiceFactory $advancedInvoiceFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        AdvancedInvoiceFactory $advancedInvoiceFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->advancedInvoiceFactory = $advancedInvoiceFactory;
    }

    /**
     * Return PDF document
     *
     * @param array|Collection $invoices
     * @return \Zend_Pdf
     */
    public function aroundGetPdf(Subject $subject, callable $proceed, $invoices = [])
    {
        if (count($invoices)) {
            /** @var \Magento\Sales\Model\Order\Invoice $invoice */
            $invoice = reset($invoices);
            if ($this->scopeConfig->getValue('sales_pdf/invoice/pdf_template', ScopeInterface::SCOPE_STORE, $invoice->getStoreId())) {
                return $this->advancedInvoiceFactory->create([
                    'invoices' => $invoices
                ]);
            }
        }
        return $proceed($invoices);
    }
}
