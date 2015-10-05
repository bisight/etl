<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;

class SplitTransformer implements TransformerInterface
{
    private $inputColumnName;
    private $outputColumns;
    private $delimiter;
    private $limit;

    /**
     * @param string $inputColumnName
     * @param string $outputColumns
     * @param string $delimiter
     */
    public function __construct($inputColumnName, $outputColumns, $delimiter, $limit = null)
    {
        $this->inputColumnName = $inputColumnName;
        $this->outputColumns = Column::unserializeArray($outputColumns);
        $this->delimiter = $delimiter;
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->outputColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(RowInterface $row)
    {
        $original = $row->get($this->inputColumnName);
        $values = explode($this->delimiter, $original, $this->limit);

        if (count($values) == count($this->outputColumns)) {
            $i = 0;
            foreach ($this->outputColumns as $column) {
                $row->set($column->getName(), $values[$i++]);
            }
        } else {
            throw new \Exception(sprintf(
                "Output columns count '%s' not equal to original columns count '%s'",
                count($this->outputColumns),
                count($values)
            ));
        }
    }
}
