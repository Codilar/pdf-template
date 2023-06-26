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
    private RendererInterface $renderer;

    /**
     * CurrencyFormat constructor.
     * @param StoreManagerInterface $storeManager
     * @param RendererInterface $renderer
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        RendererInterface $renderer
    )
    {
        $this->storeManager = $storeManager;
        $this->renderer = $renderer;
        parent::__construct($renderer);
    }

    /**
     * @inheritDoc
     */
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        @list($amount, $currencyCode) = explode(' ', $args);
        if (!$currencyCode) {
            $currencyCode = $store->getCurrentCurrency()->getCurrencySymbol();
        } else {
            $currencyCode = $this->renderer->render($currencyCode, $context) ?: $currencyCode;
            $currencyCode = $store->getCurrentCurrency()->load($currencyCode)->getCurrencySymbol();
        }
        $amount = $this->renderer->render($amount, $context);
        $currencyCode = $this->renderer->render($currencyCode, $context);
        $amount = parent::execute($template, $context, $amount, $source);
        return sprintf('%s%s', $currencyCode, $amount);
    }
}
