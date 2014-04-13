<?php
namespace Grout\AppModule\Types;

use Grout\AppModule\AppFactory;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleCommand;

class AppWebConsoleCommand extends WebConsoleCommand
{
    /** @var AppFactory */
    private $_factory;

    public function factory()
    {
        if (!$this->_factory) {
            $this->_factory = AppFactory::get($this->app);
        }

        return $this->_factory;
    }
}