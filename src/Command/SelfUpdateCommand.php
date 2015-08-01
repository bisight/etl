<?php

namespace BiSight\Etl\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BiSight\Etl\Console\Application;

class SelfUpdateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Updates bisight-etl.phar to the latest version')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!Application::isInstalledAsPhar()) {
            $output->writeln('<error>Self-update is available only for PHAR version.</error>');

            return 1;
        }

        $manager = new Manager(Manifest::loadFile(Application::MANIFEST_FILE));

        if ($manager->update($this->getApplication()->getVersion(), true)) {
            $output->writeln(sprintf(
                '<info>Application was successfully updated</info>'
            ));
        } else {
            $output->writeln(sprintf(
                '<error>Updating failed or you already have latest version</error>'
            ));
        }
    }
}
