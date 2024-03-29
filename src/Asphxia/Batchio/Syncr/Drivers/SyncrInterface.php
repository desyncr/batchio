<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Syncr\Drivers;
interface SyncrInterface {
    public function sync(Array $result);
    public function callback(Array &$result);
}