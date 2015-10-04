<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\RowInterface;
use BiSight\Etl\Column;

interface TransformerInterface
{
    /**
     * @return Column[]
     */
    public function getColumns();

    /**
     * Transform data.
     *
     * @param RowInterface $row
     */
    public function transform(RowInterface $row);
}
