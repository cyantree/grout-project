<?php
namespace Grout\AppModule\Types;

use Cyantree\Grout\App\Page;
use Grout\AppModule\AppFactory;

class AppPage extends Page
{
    /** @var AppFactory */
    private $factory;

    /** @return AppFactory */
    public function factory()
    {
        if ($this->factory === null) {
            $this->factory = AppFactory::get($this->app);
        }

        return $this->factory;
    }
}
