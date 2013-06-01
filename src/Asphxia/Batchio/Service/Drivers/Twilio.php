<?php
namespace Asphxia\Batchio\Service\Drivers;
use Asphxia\Batchio\Service\Drivers\ServiceInterface;

class Twilio implements ServiceInterface {
    private $token;
    private $sid;
    private $callerId;
    private $statusCallbackUrl;

    public function __construct($sid, $token) {
        $this->sid = $sid;
        $this->token = $token;
        $this->client = new \Services_Twilio($sid, $token);
    }
    public function setCallerId($callerId) {
        $this->callerId = $callerId;
    }
    public function setCallbackUrl($url) {
        $this->statusCallbackUrl = $url;
    }
    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }
    public function call($options, &$result) {
        $text = isset($options['message']) ? $options['message'] : 'Hello there!';

        $message = $this->client->account->sms_messages->create(
            $this->callerId, $this->recipient, $text
        );

        return $result = $message;
    }
    
}