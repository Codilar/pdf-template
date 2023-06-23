<?php


namespace Codilar\PdfTemplate\Model\Data\Mock;


class AbstractMock implements \ArrayAccess,\Stringable
{

    public function __call($name, $arguments)
    {
        if ($name === 'getData') {
            if (in_array('store_id', $arguments)) {
                return null;
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {

    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {

    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return 'mock';
    }
}
