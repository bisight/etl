<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;

class CopyTransformer implements TransformerInterface
{
    /**
     * @var string
     */
    private $inputColumnName;

    /**
     * @var Column
     */
    private $outputColumn;

    /**
     * @var boolean
     */
    private $override;

    /**
     * @param string $inputColumnName
     * @param string $outputColumn    Column definition
     * @param string $override        'true' or 'false'
     */
    public function __construct($inputColumnName, $outputColumn, $override)
    {
        $this->inputColumnName = $inputColumnName;
        $this->outputColumn = Column::unserialize($outputColumn);
        $this->override = strtolower($override) == 'true';
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return array($this->outputColumn);
    }

    /**
     * {@inheritdoc}
     */
    public function transform(RowInterface $row)
    {
        $original = $row->get($this->inputColumnName);
        $existing = $row->get($this->outputColumn->getName());

        if ($this->override || !$existing) {
            $row->set($this->outputColumn->getName(), $original);
        }
    }
}
