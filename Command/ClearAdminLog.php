<?php

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminTools\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;
use Thelia\Model\AdminLogQuery;

class ClearAdminLog extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('admin:log:clean')
            ->setDescription('Delete administrator log')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $logs = AdminLogQuery::create()->deleteAll();
            $output->writeln('Admin log successfully cleared, '.$logs.' cleared');
        } catch (\Exception $ex) {
            $output->writeln("\nError on clear admin log: " . $ex->getMessage());
        }

        return 1;
    }
}
