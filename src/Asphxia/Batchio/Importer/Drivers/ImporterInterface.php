<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Importer\Drivers;
interface ImporterInterface {
    /**
     * Set the data sources to read from.
     * 
     * @param String $filename The file name / path to read from.
     */
    public function setInput($input);
    
    /**
     * Process the input file and return an associative array of it's contents.
     * 
     * @return array
     */
    public function process();
}