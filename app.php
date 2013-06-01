<?php
require_once('vendor/autoload.php');
use Symfony\Component\Console\Application;
use Asphxia\Batchio\BatchioCommand;

$application = new Application();

$application->add(new BatchioCommand());
 
$application->run();