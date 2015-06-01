<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;

class SplitTransformer implements TransformerInterface
{
    public function __construct($inputColumnName, $outputColumnNames, $delimiter)
    {
        $this->inputColumnName = $inputColumnName;
        $this->outputColumnNames = $this->parseOutputColumnNames($outputColumnNames);
        $this->delimiter = $delimiter;
    }

    public function getColumns()
    {
        $columns = array();

        foreach ($this->outputColumnNames as $col) {
            $column = new Column();
            $column->setName(trim($col['name']));
            $column->setLength(trim($col['typelength']));
            $column->setType(strtoupper(trim($col['type'])));
            $column->setPrecision('');
            $columns[] = $column;
        }

        return $columns;
    }

    private function parseOutputColumnNames($columnNames)
    {
        $res = array();
        $cols = explode(',', $columnNames);
        foreach ($cols as $col) {
            $column = array('name', 'type', 'typelength');
            list($column['name'], $column['type']) = explode('|', $col);
            list($column['type'], $column['typelength']) = explode('-', $column['type']);
            $res []= $this->removeNumericKeys($column);
        }

        return $res;
    }

    private function removeNumericKeys($array)
    {
        foreach ($array as $key => $value) {
            if (is_int($key)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public function transform(RowInterface $row)
    {
        // var_dump($this->inputColumnName);die;
        $original = $row->get($this->inputColumnName);
        $values = explode($this->delimiter, $original);

        if (count($values) == count($this->outputColumnNames)) {
            foreach ($this->outputColumnNames as $i => $col) {
                $row->set($col['name'], $values[$i]);
            }
        }
    }
}
