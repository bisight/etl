<?php

namespace BiSight\Etl\JobLoader;

use BiSight\Etl\Job;

interface JobLoaderInterface
{
    /**
     * @param  string $filename
     * @param  array $variables
     * @return  Job[]
     */
    public function loadFile($filename, $variables);
}
