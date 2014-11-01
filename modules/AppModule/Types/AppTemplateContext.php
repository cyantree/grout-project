<?php
namespace Grout\AppModule\Types;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Generators\Template\TemplateContext;

use Cyantree\Grout\Ui\Ui;
use Grout\AppModule\AppFactory;
use Grout\AppModule\Helpers\AppQuick;

class AppTemplateContext extends TemplateContext
{
    /** @var AppFactory */
    private $factory;

    /** @var AppQuick */
    private $q;

    /** @var Ui */
    private $ui;

    /** @return AppFactory */
    public function factory()
    {
        if (!$this->factory) {
            $this->factory = AppFactory::get($this->app);
        }
        return $this->factory;
    }

    /** @return AppQuick */
    public function q()
    {
        if (!$this->q) {
            $this->q = $this->factory()->quick();
        }
        return $this->q;
    }

    /** @return Ui */
    public function ui()
    {
        if (!$this->ui) {
            $this->ui = $this->factory()->ui();
        }
        return $this->ui;
    }
}
