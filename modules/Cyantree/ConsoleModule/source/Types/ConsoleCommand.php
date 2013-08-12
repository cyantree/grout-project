<?php
namespace Grout\Cyantree\ConsoleModule\Types;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Task;
use Cyantree\Grout\Filter\ArrayFilter;

class ConsoleCommand
{
    /** @var ArrayFilter */
    public $args;

    /** @var Task */
    public $task;

    /** @var App */
    public $app;

    public $result;

    public function show($text, $newLine = true)
    {
        echo iconv('utf-8', 'cp850//IGNORE', $text);

        if($newLine){
            echo chr(10);
        }
    }

    public function execute()
    {

    }

    public function onError()
    {

    }
}