<?php

namespace BiSight\Etl\Runner;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use BiSight\Etl\Job;
use BiSight\Etl\Row;

class ConsoleRunner
{
    private $output;
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
    
    public function run(Job $job)
    {
        $extractor = $job->getExtractor();
        $transformers = $job->getTransformers();
        $loaders = $job->getLoaders();

        $count = $extractor->getCount();
        $this->output->writeLn("Rows: " . $count);
        
        $columns = $extractor->getColumns();
        
        foreach ($transformers as $transformer) {
            $columnsNew = $job->transformer->getColumns();
            $columns = array_merge($columns, $columnsNew);
        }
        
        foreach ($columns as $column) {
            $this->output->writeLn(" * <info>" . $column->getName() . "</info> " . $column->getType() . " " . $column->getLength());
        }
        
        foreach ($loaders as $loader) {
            $loader->init($columns);
            $this->output->writeLn("Loading: <info>" . $loader->getTablename() . "</info>");
        }
        
        $progress = new ProgressBar($this->output, $count);
        $progress->setFormat('very_verbose');
        $progress->start();
        
        $i = 0;
        while ($i < $count) {
            $row = new Row();
            
            $extractor->extract($row);
            
            foreach ($transformers as $transformer) {
                $transformers->transform($row);
            }
            
            foreach ($loaders as $loader) {
                $loader->load($row);
            }
            
            $progress->advance();
            $row = null;
            $i++;
        }
        
        $this->output->writeLn("");
    }
}