<?php

namespace BiSight\Etl\JobLoader;

use BiSight\Etl\Job;
use SimpleXMLElement;
use ReflectionClass;
use RuntimeException;
use DOMDocument;

class XmlJobLoader implements JobLoaderInterface
{
    public function loadFile($filename, $variables)
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
                $jobs[] = $this->loadJob($element, $variables);
                break;
            case 'jobs':
                $jobs = $this->loadJobs($element, $variables);
                break;
            default:
                throw new RuntimeException(
                    "Unsupported root element (should be job or jobs): " .
                    $element->getName()
                );
        }
        return $jobs;
    }

    public function loadJob(SimpleXMLElement $jobNode, $variables)
    {
        
        $job = new Job();
        
        $job->setName((string)$jobNode['name']);
        
        foreach ($jobNode->extractor as $extractorNode) {
            $instance = $this->getInstanceByNode($extractorNode, $variables);
            $job->setExtractor($instance);
        }
        
        foreach ($jobNode->loader as $loaderNode) {
            $instance = $this->getInstanceByNode($loaderNode, $variables);
            $job->addLoader($instance);
        }

        foreach ($jobNode->transformer as $transformerNode) {
            $instance = $this->getInstanceByNode($transformerNode, $variables);
            $job->addTransformer($instance);
        }
        return $job;

    }
    public function loadJobs(SimpleXMLElement $xml, $variables)
    {
        $jobs = array();
        foreach ($xml->job as $jobNode) {
            $job = $this->loadJob($jobNode, $variables);
            $jobs[] = $job;
        }
        return $jobs;
    }
    
    private function getInstanceByNode(SimpleXMLElement $node, $variables)
    {
        $className = (string)$node->class;
        $reflector = new ReflectionClass($className);
        $arguments = array();
        foreach ($node->argument as $argumentNode) {
            $name = (string)$argumentNode['name'];
            $value = (string)$argumentNode;
            $value = $this->processVariables($value, $variables);
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
    
    private function processVariables($string, $variables)
    {
        preg_match_all("~\{\{\s*(.*?)\s*\}\}~", $string, $arr);
        if (!$arr) {
            return $string;
        }

        foreach ($arr[1] as $varname) {
            if (!isset($variables[$varname])) {
                throw new RuntimeException("Missing variable definition: " . $varname);
            }
            $string = str_replace('{{' . $varname . '}}', $variables[$varname], $string);
        }

        return $string;
    }
}
