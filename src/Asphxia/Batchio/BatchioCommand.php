<?php
namespace Asphxia\Batchio;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BatchioCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('batchio:run')
            ->setDescription('Runs Twilio batch process')
            ->addArgument(
                'input',
                InputArgument::REQUIRED,
                'Input data to use'
            )
            ->addOption(
               'format',
               'f',
               InputOption::VALUE_OPTIONAL,
               'Input format. Currently supports only .csv'
            )
            ->addOption(
               'output-format',
               'o',
               InputOption::VALUE_OPTIONAL,
               'Output format'
            )
            ->addOption(
               'pipe',
               'p',
               InputOption::VALUE_OPTIONAL,
               'Pipe output to file'
            )
            ->addOption(
               'sync',
               's',
               InputOption::VALUE_OPTIONAL,
               'Force synchronization'
            )
            ->addOption(
               'delay',
               'd',
               InputOption::VALUE_OPTIONAL,
               'Delay between calls'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $input->getArgument('input');
        $sync = $input->getOption('sync');
        
        // TODO refactor Batchio\Importer\Drivers
        $importer = new BatchioImporter();
        $importer->setInput($data);
        
        // TODO refactor Importer\Drivers\Csv , Importer\Factory
        $importer->setDriver(new ImporterDrivers\ImporterCsv());
        $items = $importer->process();
        
        // TODO refactor BatchioTwilio -> BatchioService($driver) / BatchioService\Twilio
        $twilio = new BatchioTwilio('caller', 'statusCallbackUrl');
        $result = [];
        foreach ($items as $item) {
            $twilio->setCaller($item['number']);
            $twilio->call(array('sync' => $sync), $result);
            $output->writeln($result['message']);
        }
        
        $output->writeln('Batchio');
    }
}
