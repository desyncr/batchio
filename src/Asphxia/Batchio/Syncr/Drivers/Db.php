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
 * Provides Sync functionallity to an asychronous service
 * 
 * @package Asphxia\Batchio
 */
class Db implements SyncrInterface {
    private $dbh;
    
    /**
     * Bootstrap configuration
     * 
     * @param array $configuration
     */
    public function bootstrap(Array $configuration) {
        $this->max_attemps = $configuration['max_attemps'];
        $this->attemps_delay = $configuration['attemps_delay'];
        $hostname = $configuration['hostname'];
        $username = $configuration['username'];
        $password = $configuration['password'];
        $database = $configuration['database'];

        $this->dbh = new \PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    }

    /**
     * 
     * @param Array $result
     * @return Array
     */
    public function callback(Array &$result) {        
        $tries = 0;
        $query = $this->dbh->prepare("SELECT sid,status,account_sid as sid, call_from as `from`, call_to as `to` FROM calls where sid = :sid");

        while ($tries < $this->max_attemps) {
            // whooa, excuse me sir
            sleep($this->attemps_delay);

            // don't look at me, im a monster, arrggh
            $query->bindParam(':sid', $result['sid']);
            $query->execute();

            if (false !== $synced = $query->fetch()) {
                $result = $synced;
                break;
            }

            $tries++;
        }

        return $result;
    }
}