<?php
namespace Asphxia\Batchio;
class BatchioTwilio {
    public function __construct($callerId, $statusCallbackUrl) {
        $this->callerId = $callerId;
        $this->statusCallbackUrl = $statusCallbackUrl;
    }
    public function setCaller($caller) {
        $this->caller = $caller;
    }
    public function call($options, &$result) {
        $this->options = $options;
        $sync = $options['sync'] ? 'syncd' : 'asyc';
        $result = array('message' => 'Twilio wrapper for: ' . $this->caller . ' (' . $sync . ')');
    }
    
}