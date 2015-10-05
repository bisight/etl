<?php

namespace BiSight\Etl\Loader;

use Symfony\Component\Console\Output\OutputInterface;
use BiSight\Etl\RowInterface;

class ConsoleLoader implements LoaderInterface
{
    private $output;

    protected $columns;
    private $row = 0;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function load(RowInterface $row)
    {
        if ($this->row++ == 0) {
            foreach ($this->columns as $column) {
                $this->output->write(sprintf(
                    "%s\t",
                    $column->getAlias()
                ));
            }
        }

        foreach ($this->columns as $column) {
            $this->output->write(sprintf(
                "%s\t",
                $row->get($column->getAlias())
            ));
        }

        $this->output->writeln('');
    }

    /**
     * {@inheritdoc}
     */
    public function init($columns)
    {
        $this->columns = $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function getTablename()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup()
    {
    }
}
