<?php

namespace BiSight\Etl;

class Row implements RowInterface
{
    public function __construct()
    {

    }

    private $data = array();

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        // the value might not be set for transformer created columns
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function getArray()
    {
        return $this->data;
    }
}
