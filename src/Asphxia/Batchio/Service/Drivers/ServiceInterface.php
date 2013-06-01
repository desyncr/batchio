<?php
namespace Asphxia\Batchio\Service\Drivers;
interface ServiceInterface {
    public function setCallerId($callerId);
    public function setCallbackUrl($callbackUrl);
    public function setRecipient($recipient);
}