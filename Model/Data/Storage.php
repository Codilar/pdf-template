<?php

namespace Codilar\PdfTemplate\Model\Data;

class Storage
{
    private array $data;

    /**
     * @param array $data
     */
    public function __construct(
        array $data = []
    )
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }
}
