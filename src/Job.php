<?php

namespace BiSight\Etl;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use BiSight\Etl\Extractor\ExtractorInterface;
use BiSight\Etl\Transformer\TransformerInterface;
use BiSight\Etl\Loader\LoaderInterface;

class Job
{
    public function __construct()
    {
        
    }
    
    private $extractor;
    
    public function setExtractor(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
    }
    
    private $transformer;
    
    public function setTransformer(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }
    
    private $loader;
    
    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }
    
    
    public function run(OutputInterface $output)
    {
        $count = $this->extractor->getCount();
        $output->writeLn("Rows: " . $count);
        
        $columns = $this->extractor->getColumns();
        foreach ($columns as $column) {
            $output->writeLn(" * <info>" . $column->getName() . "</info> " . $column->getType() . " " . $column->getLength());
        }
        $this->loader->init($columns);
        
        
        $progress = new ProgressBar($output, $count);
        $progress->setFormat('very_verbose');
        $progress->start();

        $i = 0;
        while ($i < $count) {
            $row = new Row();
            $this->extractor->extract($row);
            $this->transformer->transform($row);
            $this->loader->load($row);
            $progress->advance();
            $row = null;
            $i++;
        }
        $output->writeLn("");
        
    }
}
