<?php

namespace BiSight\Etl;

class Column
{
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }

    private $length;
    
    public function getLength()
    {
        return $this->length;
    }
    
    public function setLength($length)
    {
        $this->length = $length;
    }
    
    private $type;
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    private $precision;
    
    public function getPrecision()
    {
        return $this->precision;
    }
    
    public function setPrecision($precision)
    {
        $this->precision = $precision;
    }
}
