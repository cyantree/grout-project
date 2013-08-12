<?php
namespace Grout\TestModule\Pages;

use Cyantree\Grout\App\Page;

class TestPage extends Page
{
    public function parseTask()
    {
        $this->setResult('Hello World');
    }
}
