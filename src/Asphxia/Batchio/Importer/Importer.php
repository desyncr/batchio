<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Importer;
use Asphxia\Batchio\Importer\Drivers\ImporterInterface;

/**
 * Adapter class that delegates to it's concrete implementations (so-called drivers)
 * 
 * @package Asphxia\Batchio
 */
class Importer implements ImporterInterface {
    
    /**
     * Creates an Importer object with $driver as the concrete implementation
     * 
     * @param \Asphxia\Batchio\Importer\Drivers\ImporterInterface $driver
     */
    public function __construct(ImporterInterface $driver) {
        $this->driver = $driver;
    }
    
    /**
     * Delegates to it's concrete class
     * 
     * @param String $input
     */
    public function setInput($input) {
        $this->driver->setInput($input);
    }
    
    /**
     * Delegates to it's concrete class
     * 
     * @return type
     */
    public function process() {
        return $this->driver->process();
    }
}