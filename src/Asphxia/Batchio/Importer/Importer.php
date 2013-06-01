<?php
namespace Asphxia\Batchio\Importer;
use Asphxia\Batchio\Importer\Drivers\ImporterInterface;

class Importer implements ImporterInterface {
    public function __construct(ImporterInterface $driver) {
        $this->driver = $driver;
    }
    public function setInput($input) {
        $this->driver->setInput($input);
    }
    public function process() {
        return $this->driver->process();
    }
}