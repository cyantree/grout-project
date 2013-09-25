<?php
namespace Grout\TestModule;

use Cyantree\Grout\App\Module;

class TestModule extends Module
{
    public function init()
    {
        $this->addNamedRoute('index', '', 'TestPage');
    }
}
