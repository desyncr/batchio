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
     * Sets service SID
     * 
     * @param String $sid SID @see twilio.com/user/account
     */
    public function setSid($sid) {
        $this->sid = $sid;
    }

    /**
     * Sets service token
     *
     * @param String $token Token  @see twilio.com/user/account
     */
    public function setToken($token) {
        $this->token = $token;
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
    public function call(Array $options, Array &$result, $callback = null) {
        $text = isset($options['message']) ? $options['message'] : 'Hello there!';

        $message = $this->client->account->sms_messages->create(
            $this->callerId, $this->recipient, $text, array('StatusCallback'=>$this->statusCallbackUrl)
        );

        if ($callback) $callback->callback($message);
        return $result = $message;
    }

    /**
     * Instantiates a new Twilio driver
     * 
     * @param Array $configuration
     */
    public function bootstrap(Array $configuration) {
        $this->setCallbackUrl($configuration['callbackUrl']);
        $this->setCallerId($configuration['caller']);
        $this->setSid($configuration['sid']);
        $this->setToken($configuration['token']);
        
        $this->client = new \Services_Twilio($this->sid, $this->token);
    }
    
}