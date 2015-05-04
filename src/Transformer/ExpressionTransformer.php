<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionTransformer implements TransformerInterface
{
    private $outputColumnName;
    private $expression;
    
    public function __construct($expression, $outputColumnName)
    {
        $this->outputColumnName = $outputColumnName;
        $this->expression = $expression;
        $this->language = new ExpressionLanguage();
    }
    
    
    public function getColumns()
    {
        $columns = array();
        
        $column = new Column();
        $column->setName($this->outputColumnName);
        $column->setLength(22);
        $column->setType('DOUBLE');
        $column->setPrecision('');
        $columns[] = $column;
        
        return $columns;
    }
    
    public function transform(RowInterface $row)
    {
        // TODO: Possible optimization by pre-compiling the expression on init
        $output = $this->language->evaluate($this->expression, $row->getArray());
        $row->set($this->outputColumnName, $output);
    }
}
