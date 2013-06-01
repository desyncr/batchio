<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Syncr\Drivers;
use Asphxia\Batchio\Syncr\Drivers\SyncrInterface;

/**
 * 
 * @package Asphxia\Batchio
 */
class Db implements SyncrInterface {
    public function callback(Array &$result) {
        return $result;
    }
}