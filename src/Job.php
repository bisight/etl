<?php

namespace BiSight\Etl;

use BiSight\Etl\Extractor\ExtractorInterface;
use BiSight\Etl\Transformer\TransformerInterface;
use BiSight\Etl\Loader\LoaderInterface;

class Job
{
    public function __construct()
    {
        
    }
    
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    
    private $extractor;
    
    public function setExtractor(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
    }
    
    public function getExtractor()
    {
        return $this->extractor;
    }
    
    private $transformers = array();
    
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    public function getTransformers()
    {
        return $this->transformers;
    }
    
    private $loaders = array();
    
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }
    
    public function getLoaders()
    {
        return $this->loaders;
    }
}
