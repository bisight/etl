<?php

namespace BiSight\Etl\Runner;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use BiSight\Etl\Job;
use BiSight\Etl\Row;

class ConsoleRunner
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var boolean
     */
    protected $progress = false;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param Job $job
     */
    public function run(Job $job)
    {
        $extractor = $job->getExtractor();
        $transformers = $job->getTransformers();
        $loaders = $job->getLoaders();

        $count = 0;
        $columns = array();
        if ($extractor) {
            $extractor->init();
            $count = $extractor->getCount();
            $columns = $extractor->getColumns();
        }
        $this->output->writeln('Running job: ' . $job->getName() . ' with ' . $count . ' rows');

        
        foreach ($transformers as $transformer) {
            $columnsNew = $transformer->getColumns();
            if (!is_null($columnsNew)) {
                foreach ($columnsNew as $column) {
                    // Set new column definition
                    $columns[$column->getAlias()] = $column;
                }
            }
        }

        foreach ($columns as $column) {
            $this->output->writeln(' * <info>' . $column->getName() . '</info> ' . $column->getType() . ' ' . $column->getLength());
        }

        foreach ($loaders as $loader) {
            $loader->init($columns);
            $this->output->writeln('Loading: <info>' . $loader->getTablename() . '</info>');
        }

        if ($this->progress) {
            $progress = new ProgressBar($this->output, $count);
            $progress->setFormat('very_verbose');
            $progress->start();
        }

        $i = 0;
        while ($i < $count) {
            $row = new Row();

            $extractor->extract($row);

            foreach ($transformers as $transformer) {
                $transformer->transform($row);
            }

            foreach ($loaders as $loader) {
                $loader->load($row);
            }

            if ($this->progress) {
                $progress->advance();
            }

            $row = null;
            $i++;
        }

        foreach ($loaders as $loader) {
            $loader->cleanup();
        }

        $this->output->writeln('');
    }

    /**
     * @param boolean $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }
}
