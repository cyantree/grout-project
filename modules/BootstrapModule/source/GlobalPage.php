<?php
namespace Grout\BootstrapModule;

use Cyantree\Grout\App\Page;
use Grout\BootstrapModule\GlobalFactory;

class GlobalPage extends Page
{
    /** @var GlobalFactory */
    private $_factory;

    /** @return GlobalFactory */
    public function factory()
    {
        if (!$this->_factory) {
            $this->_factory = GlobalFactory::get($this->app);
        }
        return $this->_factory;
    }
}