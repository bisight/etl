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

    /**
     * @param string $basepath
     * @param string $columns
     */
    public function __construct($basepath, $columns)
    {
        $this->basepath = $basepath;
        $this->columns = $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $dir_iterator = new RecursiveDirectoryIterator($this->basepath);
        $this->iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($this->iterator as $file) {
            $filename = $file->getPath() . '/' . $file->getFilename();
            if (is_file($filename) && ($file->getSize() > 0)) {
                $this->fileNames[] = $filename;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        return count($this->fileNames);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function extract(RowInterface $row)
    {
        $filename = $this->fileNames[$this->index++];

        $json = file_get_contents($filename);
        $data = json_decode($json, true);

        if (is_null($data)) {
            // @todo Pass to logger
            echo sprintf(
                "File '%s' content is not valid json. Skipping...",
                $filename
            );

            return;
        }

        foreach ($this->columns as $column) {
            $value = null;
            if (isset($data[$column->getName()])) {
                $value = $data[$column->getName()];
            }
            $row->set($column->getAlias(), $value);
        }
    }
}
