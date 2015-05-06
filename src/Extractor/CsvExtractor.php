<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;
use SplFileObject;
use RuntimeException;

class CsvExtractor implements ExtractorInterface
{
    private $basepath;
    private $filename;
    private $columns;
    private $count = 0;
    private $index = 0;
    private $csv;
    
    public function __construct($filename, $columns)
    {
        $this->filename = $filename;
        $this->columns = $columns;
    }

    public function init()
    {
        $this->csv  = new SplFileObject($this->filename, 'r');
        $this->csv->setCsvControl(',', '\"', '\\');
        
        while (!$this->csv->eof()) {
            $row = $this->csv->fgetcsv();
            $this->count++;
        }
        $this->csv->rewind();
    }

    public function getCount()
    {
        return $this->count;
    }
    
    public function getColumns()
    {
        return $this->columns;
    }
    
    public function extract(RowInterface $row)
    {
        $data = $this->csv->fgetcsv();
        $c = 0;
        foreach ($this->columns as $column) {
            $value = null;
            if (isset($data[$c])) {
                $value = $data[$c];
            }
            $row->set($column->getAlias(), $value);
            $c++;
        }
        $this->index++;
    }
}
