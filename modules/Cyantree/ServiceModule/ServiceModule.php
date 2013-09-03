<?php
namespace Grout\Cyantree\ServiceModule;

use Cyantree\Grout\App\Module;

class ServiceModule extends Module
{
    public function init()
    {
        $this->addNamedRoute('service', '', 'ServicePage');
    }
}