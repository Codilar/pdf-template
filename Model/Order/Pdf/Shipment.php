<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf;


use Codilar\PdfTemplate\Model\Order\Pdf\Shipment\DataProvider;
use Codilar\PdfTemplate\Model\TemplateProcessor;
use Dompdf\Dompdf;
use Dompdf\Options;
use Magento\Store\Model\StoreManagerInterface;

class Shipment extends \Zend_Pdf
{
    /**
     * @var \Magento\Sales\Model\Order\Shipment[]
     */
    private $shipments;
    /**
     * @var DataProvider
     */
    private $dataProvider;
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;
    private StoreManagerInterface $storeManager;

    /**
     * Invoice constructor.
     * @param DataProvider $dataProvider
     * @param TemplateProcessor $templateProcessor
     * @param StoreManagerInterface $storeManager
     * @param array $shipments
     * @param null $source
     * @param null $revision
     * @param bool $load
     * @throws \Zend_Pdf_Exception
     */
    public function __construct(
        DataProvider $dataProvider,
        TemplateProcessor $templateProcessor,
        StoreManagerInterface $storeManager,
        array $shipments = [],
        $source = null,
        $revision = null,
        $load = false
    )
    {
        parent::__construct($source, $revision, $load);
        $this->shipments = $shipments;
        $this->dataProvider = $dataProvider;
        $this->templateProcessor = $templateProcessor;
        $this->storeManager = $storeManager;
    }

    public function render($newSegmentOnly = false, $outputStream = null)
    {
        $pages = [];
        foreach ($this->shipments as $shipment) {
            $this->storeManager->setCurrentStore($shipment->getStoreId());
            $data = [
                'model' => $shipment
            ];
            $data = $this->dataProvider->getData($data);
            $template = $this->templateProcessor->getTemplate(TemplateProcessor::TEMPLATE_TYPE_SHIPMENT, $shipment->getStoreId());
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
