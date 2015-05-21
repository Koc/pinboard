<?php

namespace Pinboard\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TruncateCommand extends Command
{
    private $app;

    protected function configure()
    {
        $this
            ->setName('truncate')
            ->setDescription('Truncate all logged data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->app = $this->getApplication()->getSilex();
        $this->app->boot();

        $tablesForClear = array(
            'ipm_report_2_by_hostname_and_server',
            'ipm_report_by_hostname',
            'ipm_report_by_hostname_and_server',
            'ipm_report_by_server_name',
            'ipm_req_time_details',
            'ipm_mem_peak_usage_details',
            'ipm_status_details',
            'ipm_cpu_usage_details',
            'ipm_timer',
        );

        $db = $this->app['db'];
        /* @var $db Connection */

        $db->executeUpdate('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tablesForClear as $table) {
            $db->executeUpdate(sprintf('TRUNCATE %s', $table));
        }
        $db->executeUpdate('SET FOREIGN_KEY_CHECKS = 1');

        $output->writeln('<info>All data are truncated successfully</info>');
    }
}
