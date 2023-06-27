<?php


namespace Codilar\PdfTemplate\Model;

use Codilar\PdfTemplate\Model\Data\Storage;
use Handlebars\Context;
use Handlebars\Handlebars;
use Handlebars\Template;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface as StoreConfigWriter;
use Magento\Store\Model\ScopeInterface;
use Codilar\PdfTemplate\Model\TemplateProcessor\TemplateHelperInterface;


class TemplateProcessor
{
    const TEMPLATE_TYPE_INVOICE = 'invoice';
    const TEMPLATE_TYPE_SHIPMENT = 'shipment';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StoreConfigWriter
     */
    private $storeConfigWriter;
    /**
     * @var TypeListInterface
     */
    private $typeList;
    /**
     * @var TemplateHelperInterface[]
     */
    private $templateHelpers;
    /**
     * @var Storage
     */
    private $storage;

    /**
     * TemplateRenderer constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreConfigWriter $storeConfigWriter
     * @param TypeListInterface $typeList
     * @param Storage $storage
     * @param array $templateHelpers
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreConfigWriter $storeConfigWriter,
        TypeListInterface $typeList,
        Storage $storage,
        array $templateHelpers = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeConfigWriter = $storeConfigWriter;
        $this->typeList = $typeList;
        $this->templateHelpers = $templateHelpers;
        $this->storage = $storage;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getKey(string $type): string
    {
        return sprintf('pdf_template/template/%s', $type);
    }

    /**
     * @param string $type
     * @param int|null $store
     * @return string
     */
    public function getTemplate(string $type, int $store = null): string
    {
        if ($store) {
            $scope = ScopeInterface::SCOPE_STORES;
            $scopeId = $store;
        } else {
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
            $scopeId = null;
        }
        $template = (string)$this->scopeConfig->getValue($this->getKey($type), $scope, $scopeId);
        return $template;
    }

    /**
     * @param string $type
     * @param string $template
     * @param int|null $store
     * @return $this
     */
    public function setTemplate(string $type, string $template, int $store = null): TemplateProcessor
    {
        if ($store) {
            $scope = ScopeInterface::SCOPE_STORES;
            $scopeId = $store;
        } else {
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
            $scopeId = 0;
        }
        $this->storeConfigWriter->save($this->getKey($type), $template, $scope, $scopeId);
        $this->typeList->cleanType('config');
        return $this;
    }


    /**
     * @param string $str
     * @param array $data
     * @return string
     */
    public function render(string $str, $data = []): string
    {
        try {
            $handlebar = new Handlebars([
                'enableDataVariables' => true
            ]);
            foreach ($this->templateHelpers as $key => $helper) {
                $handlebar->addHelper($key, function(Template $template, Context $context, $args, $source) use (
                    $key,
                    $helper) {
                    return $this->processHelper($template, $context, $args, $source, $key, $helper);
                });
            }
            return $handlebar->render($str, $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function processHelper(Template $template, Context $context, $args, $source, string $key, TemplateHelperInterface $templateHelper)
    {
        $result = $templateHelper->execute($template, $context, $args, $source);
        $this->storage->setData($key, $result);
        return $result;
    }

    /**
     * @return array|TemplateHelperInterface[]
     */
    public function getTemplateHelpers(): array
    {
        return $this->templateHelpers;
    }
}
