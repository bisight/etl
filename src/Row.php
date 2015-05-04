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
        return $this->data[$key];
    }
    
    public function getArray()
    {
        return $this->data;
    }
}
