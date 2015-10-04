<?php

namespace BiSight\Etl\Extractor;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;
use SplFileObject;
use RuntimeException;

class CsvExtractor implements ExtractorInterface
{
    private $filename;
    private $columns;
    private $seperator;
    private $enclosure;
    private $escape;
    private $skiprows;

    private $count = 0;

    /**
     * @var SplFileObject
     */
    protected $csv;

    /**
     * @param string  $filename
     * @param array   $columns
     * @param string  $seperator
     * @param string  $enclosure
     * @param string  $escape
     * @param integer $skiprows
     */
    public function __construct(
        $filename,
        $columns,
        $seperator = ',',
        $enclosure = '"',
        $escape = '\\',
        $skiprows = 0
    ) {
        $this->filename = $filename;
        $this->columns = Column::unserializeArray($columns);
        $this->seperator = $seperator;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->skiprows = (int) $skiprows;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        return $this->count;
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
    }

}
