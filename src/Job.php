<?php

namespace BiSight\Etl;

use BiSight\Etl\Extractor\ExtractorInterface;
use BiSight\Etl\Transformer\TransformerInterface;
use BiSight\Etl\Loader\LoaderInterface;

class Job
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private $extractor;

    public function setExtractor(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }

    public function getExtractor()
    {
        return $this->extractor;
    }

    private $transformers = array();

    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    public function getTransformers()
    {
        return $this->transformers;
    }

    private $loaders = array();

    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;

        return $this;
    }

    public function getLoaders()
    {
        return $this->loaders;
    }
}
