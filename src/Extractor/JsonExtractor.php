<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RuntimeException;

class JsonExtractor implements ExtractorInterface
{
    private $basepath;
    private $fileNames = array();
    private $columns;
    private $index = 0;
    
    public function __construct($basepath, $columns)
    {
        $this->basepath = $basepath;
        $this->columns = $columns;
    }

    public function init()
    {
        $dir_iterator = new RecursiveDirectoryIterator($this->basepath);
        $this->iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($this->iterator as $file) {
            $filename = $file->getPath() . '/' . $file->getFilename();
            if (is_file($filename) && ($file->getSize()>0)) {
                $this->fileNames[] = $filename;
            }
        }
    }

    public function getCount()
    {
        return count($this->fileNames);
    }
    
    public function getColumns()
    {
        return $this->columns;
    }
    
    public function extract(RowInterface $row)
    {
        $filename = $this->fileNames[$this->index];
        
        //echo "FILE: " . $filename . "\n";
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        //print_r($data);
        foreach ($this->columns as $column) {
            $value = null;
            if (isset($data[$column->getName()])) {
                $value = $data[$column->getName()];
            }
            $row->set($column->getAlias(), $value);
        }
        $this->index++;
    }
}
