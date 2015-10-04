<?php

namespace BiSight\Etl\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    const SELF_UPDATE_PERIOD = 30;
    const MANIFEST_FILE = 'https://raw.githubusercontent.com/bisight/etl/master/manifest-v2.json';
    const NAME = 'bisight-etl.phar';
    const VERSION = '2.0.0';

    /**
     * @return boolean
     */
    public static function isInstalledAsPhar()
    {
        return 'phar://' === substr(__DIR__, 0, 7);
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (defined('APP_DEV_WARNING_TIME')) {
            $commandName = '';
            if ($name = $this->getCommandName($input)) {
                try {
                    $commandName = $this->find($name)->getName();
                } catch (\InvalidArgumentException $e) {
                }
            }

            if ($commandName !== 'self-update') {
                if (time() > APP_DEV_WARNING_TIME) {
                    $output->writeln(sprintf(
                        '<error>Warning: This build is over %s days old. It is recommended to update it by running "php %s self-update" to get the latest version.</error>',
                        self::SELF_UPDATE_PERIOD,
                        self::NAME
                    ));
                }
            }
        }

        return parent::doRun($input, $output);
    }
}
