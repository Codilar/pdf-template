<?php

namespace Codilar\PdfTemplate\Helper;


class Data
{
    /**
     * @param string $value
     * @return bool
     */
    public function isValueTruthy($value)
    {
        return in_array(strtolower($value), $this->getTruthyValues());
    }

    /**
     * @return array
     */
    public function getTruthyValues()
    {
        return [true, '1', 1, 'true', 'yes', 'y'];
    }
}
