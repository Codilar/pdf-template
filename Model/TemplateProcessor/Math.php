<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;
use Handlebars\Template;
use Magento\Framework\Exception\LocalizedException;

class Math implements TemplateHelperInterface
{

    const OPERATION_ADD = '+';
    const OPERATION_SUBTRACT = '-';
    const OPERATION_MULTIPLY = '*';
    const OPERATION_DIVIDE = '/';
    const OPERATION_MODULUS = '%';

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

    /**
     * @inheritDoc
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $result = null;
        $operands = explode(' ', $args);
        if (count($operands) <= 1) {
            throw new LocalizedException(__('Invalid syntax for math. It should be of the format {{#math operation val1 val2 val3 ...}}'));
        }
        $operation = array_shift($operands);
        $allowedOperations = [
            self::OPERATION_ADD,
            self::OPERATION_SUBTRACT,
            self::OPERATION_MULTIPLY,
            self::OPERATION_DIVIDE,
            self::OPERATION_MODULUS
        ];
        if (!in_array($operation, $allowedOperations)) {
            throw new LocalizedException(__('Invalid operation %1', $operation));
        }
        foreach ($operands as $operand) {
            $operand = floatval($this->renderer->render($operand, $context));
            if ($result === null) {
                $result = $operand;
                continue;
            }
            if ($operation == self::OPERATION_ADD) {
                $result = $result + $operand;
            } elseif ($operation == self::OPERATION_SUBTRACT) {
                $result = $result - $operand;
            } elseif ($operation == self::OPERATION_MULTIPLY) {
                $result = $result * $operand;
            } elseif ($operation == self::OPERATION_DIVIDE) {
                $result = $result / $operand;
            } elseif ($operation == self::OPERATION_MODULUS) {
                $result = $result % $operand;
            }
        }
        return $result;
    }
}
