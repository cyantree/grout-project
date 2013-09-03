<?php
namespace Grout\Cyantree\ServiceModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Service\Drivers\JsonDriver;
use Cyantree\Grout\App\Service\ServiceDriver;
use Grout\Cyantree\ServiceModule\ServiceFactory;

class ServicePage extends Page
{
    /** @var ServiceDriver */
    private $_driver;

    public function parseTask()
    {
        $this->_driver = new JsonDriver();
        $this->_driver->commandNamespaces = ServiceFactory::get($this->app, $this->task->module->id)->appConfig()->commandNamespaces;

        $this->_driver->processTask($this->task);
    }

    public function parseError($code, $data = null)
    {
        if ($this->_driver) {
            $this->_driver->processError($this->task);
        } else {
            parent::parseError($code, $data);
        }
    }
}