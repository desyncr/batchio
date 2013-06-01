<?php
namespace Asphxia\Batchio;
use Asphxia\Batchio\Service;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class Command extends \Symfony\Component\Console\Command\Command
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
        $importer = new Importer\Importer(new Importer\Drivers\Csv($this->data));
        $items = $importer->process();

        $service = new Service\Service(new Service\Drivers\Twilio($this->sid, $this->token));
        $service->setCallerId($this->callerId);
        $service->setCallbackUrl($this->callbackUrl);

        // TODO: refactor: move to service\driver and provide callback to output
        $result = [];
        foreach ($items as $item) {
            $service->setRecipient($item['number']);
            if ($this->env == 'production') $service->call(array('sync' => $this->sync), $result);

            $output->writeln(print_r($result,1));
        }
        $output->writeln('Batchio');
    }
}
