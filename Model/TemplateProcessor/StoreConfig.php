<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Handlebars\Context;
use Handlebars\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;

class StoreConfig implements TemplateHelperInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var StoreConfigDataProvider
     */
    private $storeConfigDataProvider;

    /**
     * StoreConfig constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param StoreConfigDataProvider $storeConfigDataProvider
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        StoreConfigDataProvider $storeConfigDataProvider
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->storeConfigDataProvider = $storeConfigDataProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $store = $this->storeManager->getStore();
        $storeConfigData = $this->storeConfigDataProvider->getStoreConfigData($store);
        $path = $this->replaceQuotes($args);
        if (array_key_exists($path, $storeConfigData)) {
            return $storeConfigData[$path];
        } else {
            return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $store->getId());
        }
    }

    protected function replaceQuotes(string $str)
    {
        return preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $str);
    }
}
