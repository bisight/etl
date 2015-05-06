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
    
    public function __construct(
        $filename,
        $columns,
        $seperator = ',',
        $enclosure = '"',
        $escape = '\\',
        $skiprows = 0
    ) {
        $this->filename = $filename;
        $this->columns = $columns;
        $this->seperator = $seperator;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->skiprows = (int)$skiprows;
    }

    public function init()
    {
        $this->csv = new SplFileObject($this->filename, 'r');
        
        $this->csv->setCsvControl(
            $this->seperator,
            $this->enclosure,
            $this->escape
        );
        
        while (!$this->csv->eof()) {
            $row = $this->csv->fgetcsv();
            if ($row[0] !== null) {
                $this->count++;
            }
        }
        $this->count -= $this->skiprows;
        $this->csv->rewind();
        $i = 0;
        while ($i < $this->skiprows) {
            $this->csv->fgetcsv();
            $i++;
        }
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
        if ($data[0] === null) {
            return;
        }
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
