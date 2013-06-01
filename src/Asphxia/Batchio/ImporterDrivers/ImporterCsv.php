<?php
namespace Asphxia\Batchio\ImporterDrivers;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class ImporterCsv implements ImporterInterface {
    public function import($filename) {
        $config = new LexerConfig();
        $lexer = new Lexer($config);

        $interpreter = new Interpreter();
        $interpreter->unstrict();//decissions, decissions...
        $items = [];

        $interpreter->addObserver(function(array $columns) use (&$items) {
            array_push($items, $columns);
        });

        $lexer->parse($filename, $interpreter);

        return $items;
    }
}