<?php

namespace BiSight\Etl\Transformer;

use BiSight\Etl\Column;
use BiSight\Etl\RowInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use BiSight\Etl\Transformer\ExpressionUtils;

class ExpressionTransformer implements TransformerInterface
{
    private $outputColumn;
    private $expression;
    private $language;
    private $utils;

    /**
     * @param string $expression
     * @param string $outputColumn Column definition
     */
    public function __construct($expression, $outputColumn)
    {
        $this->outputColumn = Column::unserialize($outputColumn, 'decimal', 22);
        $this->expression = $expression;

        $this->language = new ExpressionLanguage();
        $this->utils = new ExpressionUtils();
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
     *
     * @todo Possible optimization by pre-compiling the expression on init
     */
    public function transform(RowInterface $row)
    {
        $data = $row->getArray();
        $data['utils'] = $this->utils;

        $output = $this->language->evaluate($this->expression, $data);
        $row->set($this->outputColumn->getName(), $output);
    }
}
