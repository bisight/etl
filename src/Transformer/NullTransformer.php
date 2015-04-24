<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\RowInterface;

class NullTransformer implements TransformerInterface
{
    public function __construct()
    {
    }
    
    public function transform(RowInterface $row)
    {
        
    }
}
