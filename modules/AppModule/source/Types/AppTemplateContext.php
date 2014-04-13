<?php
namespace Grout\AppModule\Types;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Cyantree\Grout\App\GroutQuick;
use Cyantree\Grout\Ui\Ui;
use Grout\AppModule\AppFactory;
use Grout\AppModule\Helpers\AppQuick;

class AppTemplateContext extends TemplateContext
{
    /** @var AppFactory */
    private $_factory;

    /** @var AppQuick */
    private $_q;

    /** @var Ui */
    private $_ui;

    /** @return AppFactory */
    public function factory()
    {
        if (!$this->_factory) {
            $this->_factory = AppFactory::get($this->app);
        }
        return $this->_factory;
    }

    /** @return AppQuick */
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