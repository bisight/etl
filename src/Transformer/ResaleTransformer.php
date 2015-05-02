<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;

class ResaleTransformer implements TransformerInterface
{
    public function __construct($outputColumnName, $purchasedColumnName, $soldColumnName)
    {
        $this->outputColumnName = $outputColumnName;
        $this->purchasedColumnName = $purchasedColumnName;
        $this->soldColumnName = $soldColumnName;
    }
    
    public function getColumns()
    {
        $columns = array();
        
        $column = new Column();
        $column->setName($this->outputColumnName);
        $column->setLength(22);
        $column->setType('DOUBLE');
        $column->setPrecision('');
        $columns[] = $column;
        
        return $columns;
    }
    
    public function transform(RowInterface $row)
    {
        $purchased = $row->get($this->purchasedColumnName);
        $sold = $row->get($this->soldColumnName);
        if ($purchased>0) {
            $score = round(100 * $sold / $purchased);
        } else {
            $score = 0;
        }
        $row->set($this->outputColumnName, $score);
    }
}
