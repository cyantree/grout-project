<?php
namespace Grout\BootstrapModule;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Cyantree\Grout\App\GroutQuick;
use Cyantree\Grout\Ui\Ui;
use Grout\BootstrapModule\GlobalFactory;

class GlobalTemplateContext extends TemplateContext
{
    /** @var GlobalFactory */
    private $_factory;

    /** @var GroutQuick */
    private $_q;

    /** @var Ui */
    private $_ui;

    /** @return GlobalFactory */
    public function factory()
    {
        if (!$this->_factory) {
            $this->_factory = GlobalFactory::get($this->app);
        }
        return $this->_factory;
    }

    /** @return GroutQuick */
    public function q()
    {
        if (!$this->_q) {
            $this->_q = $this->factory()->appQuick();
        }
        return $this->_q;
    }

    /** @return Ui */
    public function ui()
    {
        if (!$this->_ui) {
            $this->_ui = $this->factory()->appUi();
        }
        return $this->_ui;
    }
}