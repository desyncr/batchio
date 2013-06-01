<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Service\Drivers;
use Asphxia\Batchio\Service\Drivers\ServiceInterface;

/**
 * Provices interoperavility interface with Twilio.
 * 
 * @package Asphxia\Batchio
 */
class Twilio implements ServiceInterface {
    private $token;
    private $sid;
    private $callerId;
    private $statusCallbackUrl;

    /**
     * Instantiates a new Twilio driver
     * 
     * @param String $sid SID @see twilio.com/user/account
     * @param String $token Token  @see twilio.com/user/account
     */
    public function __construct($sid, $token) {
        $this->sid = $sid;
        $this->token = $token;
        $this->client = new \Services_Twilio($sid, $token);
    }
    
    /**
     * Sets the caller ID
     * 
     * @param String $callerId
     */
    public function setCallerId($callerId) {
        $this->callerId = $callerId;
    }
    
    /**
     * Sets the callback Url
     * 
     * @param String $url
     */
    public function setCallbackUrl($url) {
        $this->statusCallbackUrl = $url;
    }
    
    /**
     * Sets the recipient number
     * 
     * @param String $recipient
     */
    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }
    
    /**
     * Realizes the call using Twilio library and returns immediatly.
     * 
     * @param Array $options
     * @param Array $result
     * @return Array
     */
    public function call($options, &$result) {
        $text = isset($options['message']) ? $options['message'] : 'Hello there!';

        $message = $this->client->account->sms_messages->create(
            $this->callerId, $this->recipient, $text
        );

        return $result = $message;
    }
    
}