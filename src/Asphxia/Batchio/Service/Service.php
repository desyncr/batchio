<?php
namespace Asphxia\Batchio\Service;
use Asphxia\Batchio\Service\Drivers\ServiceInterface;

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