<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Service;
use Asphxia\Batchio\Service\Drivers\ServiceInterface;

/**
 * Provices a Service adapter to various Service Drivers
 * 
 * @package Asphxia\Batchio
 */
class Service implements ServiceInterface {
    private $driver;

    public function __construct(ServiceInterface $driver) {
        $this->driver = $driver;
    }
    public function setCallerId($callerId) {
        // delegates
        $this->driver->setCallerId($callerId);
    }
    public function setCallbackUrl($callbackUrl){
        $this->driver->setCallbackUrl($callbackUrl);
    }
    public function setRecipient($recipient) {
        $this->driver->setRecipient($recipient);
    }
}