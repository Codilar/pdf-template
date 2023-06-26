<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;
use Handlebars\Template;
use Handlebars\Helpers;
use Magento\Framework\Exception\LocalizedException;

class Compare implements TemplateHelperInterface
{

    const OPERATION_EQ = 'eq';
    const OPERATION_NEQ = 'neq';
    const OPERATION_GT = 'gt';
    const OPERATION_GTEQ = 'gteq';
    const OPERATION_LT = 'lt';
    const OPERATION_LTEQ = 'lteq';

    /**
     * @var Helpers
     */
    private $handlebarHelpers;
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @param Helpers $handlebarHelpers
     * @param RendererInterface $renderer
     */
    public function __construct(
        Helpers $handlebarHelpers,
        RendererInterface $renderer
    )
    {
        $this->handlebarHelpers = $handlebarHelpers;
        $this->renderer = $renderer;
    }

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $args = explode(' ', $args);
        if (count($args) !== 3) {
            throw new LocalizedException(__('Invalid syntax for compare. It should be of the format {{#compare val1 operation val2}}'));
        }
        $firstValue = $this->renderer->render($args[0], $context);
        $secondValue = $this->renderer->render($args[2], $context);
        $operation = $args[1];

        if ($operation == self::OPERATION_EQ) {
            $conditionTrue = $firstValue == $secondValue;
        } elseif ($operation == self::OPERATION_NEQ) {
            $conditionTrue = $firstValue != $secondValue;
        } elseif ($operation == self::OPERATION_GT) {
            $conditionTrue = $firstValue > $secondValue;
        } elseif ($operation == self::OPERATION_GTEQ) {
            $conditionTrue = $firstValue >= $secondValue;
        } elseif ($operation == self::OPERATION_LT) {
            $conditionTrue = $firstValue < $secondValue;
        } elseif ($operation == self::OPERATION_LTEQ) {
            $conditionTrue = $firstValue <= $secondValue;
        } else {
            $conditionTrue = false;
        }

        return $this->handlebarHelpers->helperIf($template, $context, $conditionTrue ? 'this': '', $source);
    }
}
