<?php

namespace BiSight\Etl\JobLoader;

use BiSight\Etl\Job;
use SimpleXMLElement;
use ReflectionClass;
use RuntimeException;
use DOMDocument;

class XmlJobLoader implements JobLoaderInterface
{
    public function loadFile($filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("Jobs file not found: " . $filename);
        }
        
        $xml = file_get_contents($filename);
        
        $dom = new DOMDocument;
        $dom->loadXML($xml);
        $dom->documentURI = $filename;
        $dom->xinclude();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;

        //echo $dom->saveXML(); exit();
        $element = simplexml_import_dom($dom);
        switch ($element->getName()) {
            case 'job':
                $jobs = array();
                $jobs[] = $this->loadJob($element);
                break;
            case 'jobs':
                $jobs = $this->loadJobs($element);
                break;
            default:
                throw new RuntimeException(
                    "Unsupported root element (should be job or jobs): " .
                    $element->getName()
                );
        }
        return $jobs;
    }

    public function loadJob(SimpleXMLElement $jobNode)
    {
        $job = new Job();
        
        $job->setName((string)$jobNode['name']);
        
        foreach ($jobNode->extractor as $extractorNode) {
            $instance = $this->getInstanceByNode($extractorNode);
            $job->setExtractor($instance);
        }
        
        foreach ($jobNode->loader as $loaderNode) {
            $instance = $this->getInstanceByNode($loaderNode);
            $job->addLoader($instance);
        }

        foreach ($jobNode->transform as $transformNode) {
            $instance = $this->getInstanceByNode($transformNode);
            $job->addTransform($instance);
        }
        return $job;

    }
    public function loadJobs(SimpleXMLElement $xml)
    {
        $jobs = array();
        foreach ($xml->job as $jobNode) {
            $job = $this->loadJob($jobNode);
            $jobs[] = $job;
        }
        return $jobs;
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
                    if (!$p->isOptional()) {
                        throw new RuntimeException("Non-optional constructor argument `" . $p->getName() . "` not defined in argument list");
                    }
                } else {
                    $arguments[] = $data[$p->getName()];
                }
            }
        }
        return $arguments;
    }
}
