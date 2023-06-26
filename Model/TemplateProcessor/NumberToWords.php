<?php

namespace Codilar\PdfTemplate\Model\TemplateProcessor;

use Handlebars\Context;
use Handlebars\Template;

class NumberToWords implements TemplateHelperInterface
{

    const FORMAT_WESTERN = 'western';
    const FORMAT_INDIAN = 'indian';

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
        $args = explode(' ', $args);
        $number = $args[0] ?? 0;
        $format = $args[1] ?? '';
        if (strtolower($format) === 'indian') {
            $format = self::FORMAT_INDIAN;
        } else {
            $format = self::FORMAT_WESTERN;
        }
        $number = intval($this->renderer->render($number, $context));
        return ucfirst($this->numberToWords($number, $format));
    }

    protected function numberToWords(int $number, string $format): string
    {
        $words = [
            '0' => 'zero',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety',
            '100' => 'hundred',
            '1000' => 'thousand'
        ];

        if ($format === self::FORMAT_INDIAN) {
            $formatSpecificValues = [
                '100000' => 'lakh',
                '10000000' => 'crore'
            ];
        } else {
            $formatSpecificValues = [
                '1000000' => 'million',
                '1000000000' => 'billion',
                '1000000000000' => 'trillion'
            ];
        }

        $words = array_replace($words, $formatSpecificValues);


        if ($number < 20) {
            return $words[$number];
        }

        if ($number < 100) {
            $tens = floor($number / 10) * 10;
            $units = $number % 10;
            if ($units != 0) {
                return $words[$tens] . ' ' . $words[$units];
            } else {
                return $words[$tens];
            }
        }

        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            if ($remainder != 0) {
                return $words[$hundreds] . ' hundred and ' . $this->numberToWords($remainder, $format);
            } else {
                return $words[$hundreds] . ' hundred';
            }
        }

        $divisors = array_reverse(array_keys($formatSpecificValues));
        foreach ($divisors as $key => $divisor) {
            $divisors[$key] = intval($divisor);
        }
        $divisors[] = 1000;

        foreach ($divisors as $divisor) {
            if ($number < $divisor) {
                continue;
            }
            $quotient = floor($number / $divisor);
            $remainder = $number % $divisor;
            $quotientWords = $this->numberToWords($quotient, $format);
            $remainderWords = $this->numberToWords($remainder, $format);
            if ($remainder != 0) {
                return $quotientWords . ' ' . $words[$divisor] . ' ' . $remainderWords;
            } else {
                return $quotientWords . ' ' . $words[$divisor];
            }
        }
    }
}
