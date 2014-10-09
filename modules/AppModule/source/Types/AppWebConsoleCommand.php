<?php
namespace Grout\AppModule\Types;

use Grout\AppModule\AppFactory;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleCommand;

class AppWebConsoleCommand extends WebConsoleCommand
{
    /** @var AppFactory */
    private $factory;

    public function factory()
    {
        if (!$this->factory) {
            $this->factory = AppFactory::get($this->app);
        }

        return $this->factory;
    }
}
