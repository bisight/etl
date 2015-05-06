<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use BiSight\Etl\Transformer\ExpressionUtils;

class ExpressionTransformer implements TransformerInterface
{
    private $outputColumnName;
    private $expression;
    private $utils;
    
    public function __construct($expression, $outputColumnName)
    {
        $this->outputColumnName = $outputColumnName;
        $this->expression = $expression;
        $this->language = new ExpressionLanguage();
        $this->utils = new ExpressionUtils();
    }
    
    
    public function getColumns()
    {
        $columns = array();
        
        $column = new Column();
        $column->setName($this->outputColumnName);
        $column->setLength(22);
        $column->setType('DOUBLE');
        $column->setPrecision('');
        $columns[$column->getAlias()] = $column;
        
        return $columns;
    }
    
    public function transform(RowInterface $row)
    {
        // TODO: Possible optimization by pre-compiling the expression on init
        $data = $row->getArray();
        $data['utils'] = $this->utils;

        $output = $this->language->evaluate($this->expression, $data);
        $row->set($this->outputColumnName, $output);
    }
}
