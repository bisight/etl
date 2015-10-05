<?php

namespace BiSight\Etl\test\Console;

use BiSight\Etl\Console\Application;
use BiSight\Etl\Command\SelfUpdateCommand;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testDevWarning()
    {
        $application = new Application;

        $inputMock = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $outputMock = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $outputMock->expects($this->once())
            ->method("writeln")
            ->with($this->equalTo(sprintf(
                '<error>Warning: This build is over %s days old. It is recommended to update it by running "php %s self-update" to get the latest version.</error>',
                Application::SELF_UPDATE_PERIOD,
                Application::NAME
            )));

        if (!defined('APP_DEV_WARNING_TIME')) {
            define('APP_DEV_WARNING_TIME', time() - 1);
        }

        $application->doRun($inputMock, $outputMock);
    }

    public function ensureNoDevWarningOnSelfUpdate($command)
    {
        $application = new Application;
        $application->add(new SelfUpdateCommand);
        $inputMock = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $outputMock = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $inputMock->expects($this->once())
            ->method('getFirstArgument')
            ->will($this->returnValue($command));

        $outputMock->expects($this->never())
            ->method("writeln");

        if (!defined('APP_DEV_WARNING_TIME')) {
            define('APP_DEV_WARNING_TIME', time() - 1);
        }

        $application->doRun($inputMock, $outputMock);
    }
}
