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
        if ($value) {
            return in_array(strtolower($value), $this->getTruthyValues());
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getTruthyValues()
    {
        return [true, '1', 1, 'true', 'yes', 'y'];
    }
}
