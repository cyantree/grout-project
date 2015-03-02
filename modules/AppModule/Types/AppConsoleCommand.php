<?php
namespace Grout\AppModule\Types;

use Grout\AppModule\AppFactory;
use Grout\Cyantree\UniversalConsoleModule\Types\UniversalConsoleCommand;

class AppConsoleCommand extends UniversalConsoleCommand
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
