<?php

namespace BiSight\Etl;

class Row implements RowInterface
{
    private $data = array();

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        // the value might not be set for transformer created columns
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getArray()
    {
        return $this->data;
    }
}
