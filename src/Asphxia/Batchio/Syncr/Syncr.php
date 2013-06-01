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

    public function __construct(SyncrInterface $driver) {
        $this->driver = $driver;
    }
    public function addObserver($function) {
        array_push($this->observers, $function);
    }
    public function callback(Array &$result) {
        $result = $this->driver->callback($result);
        foreach ($this->observers as $observer) {
            call_user_func($observer, $result);
        }
        return $result;
    }
}