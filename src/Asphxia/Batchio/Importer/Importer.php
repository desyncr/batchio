<?php
namespace Asphxia\Batchio\Importer;
use Asphxia\Batchio\Importer\Drivers\DriverInterface;

class Importer {
    public function setInput($input) {
        $this->input = $input;
    }
    public function setDriver(DriverInterface $driver) {
        $this->driver = $driver;
    }
    public function process() {
        return $this->driver->import($this->input);
    }
}