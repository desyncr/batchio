<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Service\Drivers;
interface ServiceInterface {
    /**
     * Sets the caller ID
     * 
     * @param String $callerId
     */
    public function setCallerId($callerId);
    
    /**
     * Sets the callback URL
     * 
     * @param String $callbackUrl
     */
    public function setCallbackUrl($callbackUrl);
    
    /**
     * Sets the recipient number
     * 
     * @param String $recipient
     */
    public function setRecipient($recipient);
}