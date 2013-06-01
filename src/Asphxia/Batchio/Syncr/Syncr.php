<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Syncr;
use Asphxia\Batchio\Syncr\Drivers\SyncrInterface;

/**
 * 
 * @package Asphxia\Batchio
 */
class Syncr implements SyncrInterface {
    private $driver;
    private $observers = [];

    /**
     * Sets the syncr driver
     *
     * @param \Asphxia\Batchio\Syncr\Drivers\SyncrInterface $driver
     */
    public function __construct(SyncrInterface $driver) {
        $this->driver = $driver;
    }
    
    /**
     * 
     * @param Array $configuration
     */
    public function bootstrap(Array $configuration) {
        $this->driver->bootstrap($configuration);
    }
    
    /**
     * Adds a new observer
     * 
     * @param Callable $function
     */
    public function addObserver($function) {
        // TODO make observers removable
        array_push($this->observers, $function);
    }
    
    /**
     * Calls the drivers callback function to process result
     * 
     * @param Array $result
     * @return Array
     */
    public function callback(Array &$result) {
        $result = $this->driver->callback($result);
        foreach ($this->observers as $observer) {
            call_user_func($observer, $result);
        }
        return $result;
    }
}