<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor\Renderer;

use Codilar\PdfTemplate\Model\TemplateProcessor\RendererInterface;
use Handlebars\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class StoreConfig implements RendererInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheirtDoc
     */
    public function render(string $value, Context $context)
    {
        if ($value) {
            return (string)$this->scopeConfig->getValue($value, ScopeInterface::SCOPE_STORE);
        } else {
            return '';
        }
    }
}
