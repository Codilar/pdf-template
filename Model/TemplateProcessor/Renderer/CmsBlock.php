<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor\Renderer;

use Codilar\PdfTemplate\Model\TemplateProcessor\RendererInterface;
use Handlebars\Context;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Widget\Model\Template\FilterEmulate as WidgetFilter;

class CmsBlock implements RendererInterface
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;
    /**
     * @var WidgetFilter
     */
    private $widgetFilter;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param WidgetFilter $widgetFilter
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        WidgetFilter $widgetFilter
    )
    {
        $this->blockRepository = $blockRepository;
        $this->widgetFilter = $widgetFilter;
    }

    /**
     * @inheirtDoc
     */
    public function render(string $value, Context $context)
    {
        try {
            $block = $this->blockRepository->getById($value);
            return $this->widgetFilter->filter($block->getContent());
        } catch (\Exception $e) {
            return '';
        }
    }
}
