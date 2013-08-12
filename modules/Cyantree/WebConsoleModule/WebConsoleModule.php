<?php
namespace Grout\Cyantree\WebConsoleModule;

use Cyantree\Grout\App\Module;

class WebConsoleModule extends Module
{
    public function init()
    {
        $this->addNamedRoute('console', '', 'WebConsolePage');
    }

}