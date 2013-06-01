<?php
namespace Asphxia\Batchio;
use Asphxia\Batchio\ImporterDrivers\ImporterInterface;

class BatchioImporter {
    public function setInput($input) {
        $this->input = $input;
    }
    public function setDriver(ImporterInterface $driver) {
        $this->driver = $driver;
    }
    public function process() {
        return $this->driver->import($this->input);
    }
}