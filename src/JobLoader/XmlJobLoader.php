<?php

namespace BiSight\Etl\JobLoader;

use BiSight\Etl\Job;
use SimpleXMLElement;
use ReflectionClass;
use RuntimeException;

class XmlJobLoader implements JobLoaderInterface
{
    public function loadFile($filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("Job file not found: " . $filename);
        }
        $xml = simplexml_load_file($filename);
        return $this->load($xml);
    }

    public function load(SimpleXMLElement $xml)
    {
        $job = new Job();
        
        $job->setName((string)$xml['name']);
        
        foreach ($xml->extractor as $node) {
            $instance = $this->getInstanceByNode($node);
            $job->setExtractor($instance);
        }
        
        foreach ($xml->loader as $node) {
            $instance = $this->getInstanceByNode($node);
            $job->addLoader($instance);
        }

        foreach ($xml->transform as $node) {
            $instance = $this->getInstanceByNode($node);
            $job->addTransform($instance);
        }

        return $job;
    }
    
    private function getInstanceByNode(SimpleXMLElement $node)
    {
        $className = (string)$node->class;
        $reflector = new ReflectionClass($className);
        $arguments = array();
        foreach ($node->argument as $argumentNode) {
            $name = (string)$argumentNode['name'];
            $value = (string)$argumentNode;
            $arguments[$name] = $value;
        }
        $method = $reflector->getConstructor();
    
        $instance = $reflector->newInstanceArgs($this->getMethodArguments($method, $arguments));
        return $instance;
    }
    
    private function getMethodArguments($method, $data)
    {
        $arguments = array();
        // Inject requested constructor arguments
        if ($method) {
            foreach ($method->getParameters() as $p) {
                if (!isset($data[$p->getName()])) {
                    throw new RuntimeException("Constructor argument `" . $p->getName() . "` not defined in argument list");
                }
                $arguments[] = $data[$p->getName()];
            }
        }
        return $arguments;
    }
}
