<?php
namespace Grout\Cyantree\ConsoleModule;

use Cyantree\Grout\App\GroutFactory;
use Grout\BootstrapModule\GlobalFactory;
use Grout\Cyantree\ConsoleModule\Types\ConsoleConfig;

class ConsoleFactory extends GlobalFactory
{
    /** @return ConsoleFactory */
    public static function get($app)
    {
        /** @var ConsoleFactory $factory */
        $factory = GroutFactory::_getInstance($app, __CLASS__);

        return $factory;
    }

    public function appConfig()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $m = $this->app->getModuleByType('Cyantree\ConsoleModule');

        /** @var ConsoleConfig $tool */
        $tool = $this->app->config->get('Cyantree\ConsoleModule', 'Cyantree\ConsoleModule', new ConsoleConfig());

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }
}