<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor\Renderer;

use Codilar\PdfTemplate\Model\TemplateProcessor\RendererInterface;
use Handlebars\Context;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Widget\Model\Template\FilterEmulate as WidgetFilter;

class CmsBlock implements RendererInterface
{
    private BlockRepositoryInterface $blockRepository;
    private WidgetFilter $widgetFilter;

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

    public function render(string $value, Context $context): string
    {
        try {
            $block = $this->blockRepository->getById($value);
            return $this->widgetFilter->filter($block->getContent());
        } catch (\Exception) {
            return '';
        }
    }
}
