<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\VarDumper\VarDumper;

class CurrencyFormat extends NumberFormat implements TemplateHelperInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * CurrencyFormat constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        /** @var DataObject $model */
        $model = $context->get('model');
        if ($model) {
            $storeId = $model->getData('store_id');
        } else {
            $storeId = 0;
        }
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore($storeId);
        @list($amount, $currencyCode) = explode(' ', $args);
        if (!$currencyCode) {
            $currencyCode = $store->getCurrentCurrency()->getCurrencySymbol();
        } else {
            $currencyCode = $context->get($currencyCode) ?: $currencyCode;
            $currencyCode = $store->getCurrentCurrency()->load($currencyCode)->getCurrencySymbol();
        }
        $amount = $context->get($amount) ?: $amount;
        $currencyCode = $context->get($currencyCode) ?: $currencyCode;
        $amount = parent::execute($template, $context, $amount, $source);
        return sprintf('%s%s', $currencyCode, $amount);
    }
}
