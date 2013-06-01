<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio;
use Asphxia\Batchio\Service;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Basic command to make Twilio/etc Service calls on batches
 * 
 * @package Asphxia\Batchio
 */
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

    /**
     * Reads configuration arguments and options and process configuration files.
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);

        $this->data = $input->getArgument('input');
        
        $configFile = $input->getArgument('config');
        $configuration = Yaml::parse($configFile);

        // TODO: refactor: remove twilio -> $c[$c['service']]['sid'] etc, move to server's configuration
        $this->service['driver'] = $driver = $configuration['service']['driver'];
        $this->service['configuration'] = $configuration['service'][$driver];

        $this->sync = $input->getOption('sync');
        $this->syncr = null;

        if ($this->sync) {
            $syncr['driver'] = $driver = $configuration['syncr']['driver'];
            $syncr['configuration'] = $configuration['syncr'][$driver];
            
            $this->syncr = new Syncr\Syncr(new Syncr\Drivers\Db());
            $this->syncr->bootstrap($syncr['configuration']);

            $this->syncr->addObserver(function ($result) use ($output) {
                $sid    = $result['sid'];
                $to     = $result['to'];
                $from   = $result['from'];
                $status = $result['status'];

                $output->writeln("$sid\t$to\t$from\t$status");
            });
        }
        
        $this->env = $configuration['env'];
    }

    /**
     * Process data input (ie CSV) and batch process them.
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $importer = new Importer\Importer(new Importer\Drivers\Csv($this->data));
        $items = $importer->process();

        // TODO: refactor: Service\Factory::create($configuration['service'])
        $service = new Service\Service(new Service\Drivers\Twilio());
        $service->bootstrap($this->service['configuration']);

        // TODO: refactor: move to service\driver and provide callback to output
        $result = [];
        foreach ($items as $item) {
            $service->setRecipient($item['number']);
            if ($this->env == 'production') {
                $service->call(array('sync' => $this->sync), $result, $this->syncr);
            }
        }
        $output->writeln('Batchio');
    }
}
