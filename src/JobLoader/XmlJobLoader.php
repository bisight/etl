<?php

namespace BiSight\Etl\JobLoader;

use BiSight\Etl\Job;
use BiSight\Etl\Column;
use SimpleXMLElement;
use ReflectionClass;
use RuntimeException;
use DOMDocument;

class XmlJobLoader implements JobLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadFile($filename, $variables)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf(
                'Jobs file "%s" not found.',
                $filename
            ));
        }

        $xml = file_get_contents($filename);

        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $dom->documentURI = $filename;
        $dom->xinclude();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;

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
                throw new RuntimeException(sprintf(
                    "Unsupported root element '%s'. Should be 'job' or 'jobs'.",
                    $element->getName()
                ));
        }

        return $jobs;
    }

    /**
     * @param  SimpleXMLElement $jobNode
     * @param  array            $variables
     * @return Job
     */
    public function loadJob(SimpleXMLElement $jobNode, $variables)
    {
        $job = new Job();

        $job->setName((string) $jobNode['name']);

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

    /**
     * @param  SimpleXMLElement $xml
     * @param  array            $variables
     * @return Job[]
     */
    public function loadJobs(SimpleXMLElement $xml, $variables)
    {
        $jobs = array();

        foreach ($xml->job as $jobNode) {
            $job = $this->loadJob($jobNode, $variables);
            $jobs[] = $job;
        }

        return $jobs;
    }

    /**
     * @param  SimpleXMLElement $node
     * @param  array            $variables
     * @return object
     */
    private function getInstanceByNode(SimpleXMLElement $node, $variables)
    {
        $className = (string) $node->class;
        $reflector = new ReflectionClass($className);

        $arguments = array();
        foreach ($node->argument as $argumentNode) {
            $name = (string) $argumentNode['name'];

            // switch ($name) {
            //     case 'columns':
            //         $value = $this->processColumns($argumentNode);
            //         break;

            //     default:
            $value = $this->processVariables((string) $argumentNode, $variables);
            // }
            $arguments[$name] = $value;
        }

        $method = $reflector->getConstructor();
        return $reflector->newInstanceArgs($this->getMethodArguments($method, $arguments));
    }

    /**
     * @param  object $method
     * @param  array  $data
     * @return array
     */
    private function getMethodArguments($method, $data)
    {
        $arguments = array();

        // Inject requested constructor arguments
        if ($method) {
            foreach ($method->getParameters() as $p) {
                if (!isset($data[$p->getName()])) {
                    if (!$p->isOptional()) {
                        throw new RuntimeException(sprintf(
                            "Non-optional constructor argument `%s` not defined in argument list",
                            $p->getName()
                        ));
                    }
                    $arguments[] = $p->getDefaultValue();
                } else {
                    $arguments[] = $data[$p->getName()];
                }
            }
        }

        return $arguments;
    }

    /**
     * @param  string $string
     * @param  array  $variables
     * @return string
     */
    private function processVariables($string, $variables)
    {
        preg_match_all("~\{\{\s*(.*?)\s*\}\}~", $string, $arr);
        if (!$arr) {
            return $string;
        }

        foreach ($arr[1] as $varname) {
            if (!isset($variables[$varname])) {
                throw new RuntimeException(sprintf(
                    'Missing variable definition: %s',
                    $varname
                ));
            }
            $string = str_replace('{{' . $varname . '}}', $variables[$varname], $string);
        }

        return $string;
    }

    // private function processColumns($node)
    // {
    //     $columns = array();
    //     foreach ($node->column as $columnNode) {
    //         $column = new Column();
    //         $column->setName((string) $columnNode['name']);
    //         $column->setAlias((string) $columnNode['alias']);
    //         $column->setType((string) $columnNode['type']);
    //         $column->setLength((string) $columnNode['length']);
    //         $columns[$column->getAlias()] = $column;
    //     }
    //     return $columns;
    // }
}
