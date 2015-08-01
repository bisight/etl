<?php

namespace BiSight\Etl\Compiler;

use Symfony\Component\Process\Process;
use Secondtruth\Compiler\Compiler as BaseCompiler;

class Compiler extends BaseCompiler
{
    protected function getVersionDate()
    {
        $process = new Process('git log -n1 --pretty=%ci HEAD', __DIR__);
        if ($process->run() != 0) {
            throw new \RuntimeException('Can\'t run git log. You must ensure to run compile from git repository clone and that git binary is available.');
        }
        $versionDate = new \DateTime(trim($process->getOutput()));
        $versionDate->setTimezone(new \DateTimeZone('UTC'));

        return $versionDate;
    }

    protected function generateStub($name)
    {
        $stub = array('#!/usr/bin/env php', '<?php');
        $stub[] = "Phar::mapPhar('$name');";

        $versionDate = $this->getVersionDate();
        $warningTime = $versionDate->format('U') + 30 * 86400;

        $stub[] = sprintf("define('APP_DEV_RELEASE_DATE', '%s');\n", $versionDate->format('Y-m-d H:i:s'));
        $stub[] = sprintf("define('APP_DEV_WARNING_TIME', '%s');\n", $warningTime);

        $stub[] = "if (PHP_SAPI == 'cli') {";
        if (isset($this->index['cli'])) {
            $file = $this->index['cli'][0];
            $stub[] = " require 'phar://$name/$file';";
        } else {
            $stub[] = " exit('This program can not be invoked via the CLI version of PHP, use the Web interface instead.'.PHP_EOL);";
        }
        $stub[] = '} else {';
        if (isset($this->index['web'])) {
            $file = $this->index['web'][0];
            $stub[] = " require 'phar://$name/$file';";
        } else {
            $stub[] = " exit('This program can not be invoked via the Web interface, use the CLI version of PHP instead.'.PHP_EOL);";
        }
        $stub[] = '}';
        $stub[] = '__HALT_COMPILER();';

        return implode("\n", $stub);
    }
}
