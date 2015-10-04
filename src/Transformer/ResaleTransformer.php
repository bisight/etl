<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;

class ResaleTransformer implements TransformerInterface
{
    private $outputColumn;
    private $purchasedColumnName;
    private $soldColumnName;

    /**
     * @param string $outputColumn        Column definition
     * @param string $purchasedColumnName
     * @param string $soldColumnName
     */
    public function __construct($outputColumn, $purchasedColumnName, $soldColumnName)
    {
        $this->outputColumn = Column::unserialize($outputColumn, 'decimal', 22);
        $this->purchasedColumnName = $purchasedColumnName;
        $this->soldColumnName = $soldColumnName;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return Column::reassignAliases(array(
            $this->outputColumn
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function transform(RowInterface $row)
    {
        $purchased = $row->get($this->purchasedColumnName);
        $sold = $row->get($this->soldColumnName);
        if ($purchased > 0) {
            $score = round(100 * $sold / $purchased);
        } else {
            $score = 0;
        }
        $row->set($this->outputColumnName, $score);
    }
}
