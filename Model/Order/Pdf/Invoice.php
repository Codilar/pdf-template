<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf;


use Codilar\PdfTemplate\Model\Order\Pdf\Invoice\DataProvider;
use Codilar\PdfTemplate\Model\TemplateProcessor;
use Dompdf\Dompdf;
use Dompdf\Options;
use Magento\Store\Model\StoreManagerInterface;

class Invoice extends \Zend_Pdf
{
    /**
     * @var \Magento\Sales\Model\Order\Invoice[]
     */
    private $invoices;
    /**
     * @var DataProvider
     */
    private $dataProvider;
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Invoice constructor.
     * @param DataProvider $dataProvider
     * @param TemplateProcessor $templateProcessor
     * @param array $invoices
     * @param StoreManagerInterface $storeManager
     * @param null $source
     * @param null $revision
     * @param bool $load
     * @throws \Zend_Pdf_Exception
     */
    public function __construct(
        DataProvider $dataProvider,
        TemplateProcessor $templateProcessor,
        StoreManagerInterface $storeManager,
        array $invoices = [],
        $source = null,
        $revision = null,
        $load = false
    )
    {
        parent::__construct($source, $revision, $load);
        $this->invoices = $invoices;
        $this->dataProvider = $dataProvider;
        $this->templateProcessor = $templateProcessor;
        $this->storeManager = $storeManager;
    }

    public function render($newSegmentOnly = false, $outputStream = null)
    {
        $pages = [];
        foreach ($this->invoices as $invoice) {
            $this->storeManager->setCurrentStore($invoice->getStoreId());
            $data = [
                'model' => $invoice
            ];
            $data = $this->dataProvider->getData($data);
            $template = $this->templateProcessor->getTemplate(TemplateProcessor::TEMPLATE_TYPE_INVOICE, $invoice->getStoreId());
            $pages[] = $this->templateProcessor->render($template, $data);
        }

        $dompdf = $this->getDomPdfInstance();
        $dompdf->loadHtml(implode('<br>', $pages));
        $dompdf->render();
        return $dompdf->output();
    }

    /**
     * @return Dompdf
     */
    protected function getDomPdfInstance(): Dompdf
    {
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled( true);
        $dompdf = new Dompdf($options);
        return $dompdf;
    }

}
