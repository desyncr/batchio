<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @copyright Copyleft
 */
namespace Asphxia\Batchio\Importer\Drivers;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
/**
 * Provides a CSV input feature. Reads CSV files through Goodby's CSV library.
 * 
 * @package Asphxia\Batchio
 */
class Csv implements ImporterInterface {
    /**
     * Instantiates the driver.
     * 
     * @param String $filename The file name / path to read from.
     */
    public function __construct($filename) {
        $this->setInput($filename);
    }
    
    /**
     * Set the data sources to read from.
     * 
     * @param String $filename The file name / path to read from.
     */
    public function setInput($filename) {
        $this->filename = $filename;
    }
    
    /**
     * Process the input file and return an associative array of it's contents.
     * 
     * @return array
     */
    public function process() {
        $config = new LexerConfig();
        $lexer = new Lexer($config);

        $interpreter = new Interpreter();
        $interpreter->unstrict();//decissions, decissions...
        $items = [];

        $interpreter->addObserver(function(array $columns) use (&$items) {
            $element = array(
                'number'  => $columns[0]
            );
            array_push($items, $element);
        });

        $lexer->parse($this->filename, $interpreter);

        return $items;
    }
}