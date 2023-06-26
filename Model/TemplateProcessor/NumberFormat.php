<?php


namespace Codilar\PdfTemplate\Model\TemplateProcessor;


use Handlebars\Context;
use Handlebars\Helpers;
use Handlebars\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Codilar\PdfTemplate\Helper\Data;

class NumberFormat implements TemplateHelperInterface
{

    const DEFAULT_PRECISION = 2;
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer
    )
    {
        $this->renderer = $renderer;
    }

    public function execute(Template $template, Context $context, $args, $source)
    {
        @list($number, $precision) = explode(' ', $args);
        $number = $this->renderer->render($number, $context);
        if ($precision) {
            $precision = $this->replaceQuotes($precision);
            $precision = $this->renderer->render($precision, $context);
        } else {
            $precision = self::DEFAULT_PRECISION;
        }
        return @number_format((float)$number, $precision, '.', '');
    }

    protected function replaceQuotes(string $str)
    {
        return preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $str);
    }
}
