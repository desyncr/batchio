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
            ->addArgument(
               'sid',
               InputArgument::REQUIRED,
               'Twilio SID'
            )
            ->addArgument(
               'token',
               InputArgument::REQUIRED,
               'Twilio Token'
            )
            ->addArgument(
               'caller',
               InputArgument::REQUIRED,
               'Caller Id'
            )
            ->addOption(
               'callbackUrl',
               null,
               InputOption::VALUE_REQUIRED,
               'Status Callback URI'
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
        
        $sid = $input->getArgument('sid');
        $token = $input->getArgument('token');
        $callerId = $input->getArgument('caller');
        $callbackUrl = $input->getOption('callbackUrl');

        $sync = $input->getOption('sync');
        
        // TODO refactor Batchio\Importer\Drivers
        $importer = new BatchioImporter();
        $importer->setInput($data);
        
        // TODO refactor Importer\Drivers\Csv , Importer\Factory
        $importer->setDriver(new ImporterDrivers\ImporterCsv());
        $items = $importer->process();
        
        // TODO refactor BatchioTwilio -> Batchio\Service($driver) / Batchio\Service\Twilio
        $twilio = new BatchioTwilio($sid, $token);
        $twilio->setCallerId($callerId);
        $twilio->setStatusCallbackUrl($callbackUrl);

        $result = [];
        foreach ($items as $item) {
            $twilio->setRecipient($item['number']);
            $twilio->call(array('sync' => $sync), $result);
            $output->writeln(print_r($result,1));
        }
        
        $output->writeln('Batchio');
    }
}
