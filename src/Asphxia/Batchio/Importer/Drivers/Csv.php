<?php
namespace Asphxia\Batchio\Importer\Drivers;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class Csv implements DriverInterface {
    public function import($filename) {
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

        $lexer->parse($filename, $interpreter);

        return $items;
    }
}