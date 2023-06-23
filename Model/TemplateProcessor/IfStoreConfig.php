<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Handlebars\Context;
use Handlebars\Helpers;
use Handlebars\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Codilar\PdfTemplate\Helper\Data;

class IfStoreConfig extends StoreConfig
{

    /**
     * @var Data
     */
    private $helper;
    /**
     * @var Helpers
     */
    private $handlebarHelpers;

    /**
     * IfStoreConfig constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param StoreConfigDataProvider $storeConfigDataProvider
     * @param Data $helper
     * @param Helpers $handlebarHelpers
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        StoreConfigDataProvider $storeConfigDataProvider,
        Data $helper,
        Helpers $handlebarHelpers
    )
    {
        parent::__construct($scopeConfig, $storeManager, $storeConfigDataProvider);
        $this->helper = $helper;
        $this->handlebarHelpers = $handlebarHelpers;
    }

    public function execute(Template $template, Context $context, $args, $source)
    {
        @list($path, $value) = explode(' ', $args);
        $storeConfig = parent::execute($template, $context, $path, $source);

        if ($value) {
            $value = $this->replaceQuotes($value);
            $value = $context->get($value) ?: $value;
            $conditionMatched = ($storeConfig == $value);
        } else {
            $conditionMatched = $this->helper->isValueTruthy($storeConfig);
        }
        return $this->handlebarHelpers->helperIf($template, $context, $conditionMatched ? 'this': '', $source);
    }
}
