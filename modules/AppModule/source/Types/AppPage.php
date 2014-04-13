<?php
namespace Grout\AppModule\Types;

use Cyantree\Grout\App\Page;
use Grout\AppModule\AppFactory;

class AppPage extends Page
{
    /** @var AppFactory */
    private $_factory;

    /** @return AppFactory */
    public function factory()
    {
        if ($this->_factory === null) {
            $this->_factory = AppFactory::get($this->app);
        }

        return $this->_factory;
    }
}