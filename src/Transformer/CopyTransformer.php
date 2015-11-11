<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;

class CopyTransformer implements TransformerInterface
{
    public function __construct($inputColumnName, $outputColumnName, $override)
    {
        $this->inputColumnName = $inputColumnName;
        $this->outputColumnName = $outputColumnName;
        $this->override = strtolower($override) == 'true';
    }

    public function getColumns()
    {
        $columns = array();

        return $columns;
    }

    public function transform(RowInterface $row)
    {
        $original = $row->get($this->inputColumnName);
        $existing = $row->get($this->outputColumnName);

        if ($this->override || !$existing) {
            $row->set($this->outputColumnName, $original);
        }
    }
}
