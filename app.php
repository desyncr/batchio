<?php
require_once('vendor/autoload.php');
use Symfony\Component\Console\Application;
use Asphxia\Batchio;

$application = new Application();

$application->add(new Batchio\Command());
 
$application->run();