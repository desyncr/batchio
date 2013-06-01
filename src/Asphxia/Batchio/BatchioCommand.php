<?php
namespace Asphxia\Batchio;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

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
               'config',
               InputArgument::REQUIRED,
               'Twilio Configuration'
            )
            ->addOption(
               'sync',
               's',
               InputOption::VALUE_OPTIONAL,
               'Force synchronization'
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);

        $this->data = $input->getArgument('input');
        
        $configFile = $input->getArgument('config');
        $configuration = Yaml::parse($configFile);

        $this->sid = $configuration['twilio']['sid'];
        $this->token = $configuration['twilio']['token'];
        $this->callerId = $configuration['twilio']['caller'];
        $this->callbackUrl = $configuration['twilio']['callbackUrl'];

        $this->sync = $input->getOption('sync');
        
        $this->env = $configuration['env'];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        // TODO refactor Batchio\Importer\Drivers
        $importer = new Importer\Importer();
        $importer->setInput($this->data);
        
        // TODO refactor Importer\Drivers\Csv , Importer\Factory
        $importer->setDriver(new Importer\Drivers\Csv());
        $items = $importer->process();
        
        // TODO refactor BatchioTwilio -> Batchio\Service($driver) / Batchio\Service\Twilio
        $twilio = new BatchioTwilio($this->sid, $this->token);
        $twilio->setCallerId($this->callerId);
        $twilio->setStatusCallbackUrl($this->callbackUrl);

        $result = [];
        foreach ($items as $item) {
            $twilio->setRecipient($item['number']);
            if ($this->env == 'production') $twilio->call(array('sync' => $this->sync), $result);

            $output->writeln(print_r($result,1));
        }
        $output->writeln('Batchio');
    }
}
