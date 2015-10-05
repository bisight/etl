<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\RowInterface;

class NullTransformer implements TransformerInterface
{
    public function getColumns()
    {
    }

    public function transform(RowInterface $row)
    {
    }
}
