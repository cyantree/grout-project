<?php
namespace Grout\Cyantree\WebConsoleModule;

use Cyantree\Grout\App\GroutFactory;
use Grout\BootstrapModule\GlobalFactory;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleConfig;

class WebConsoleFactory extends GlobalFactory
{
    /** @return WebConsoleFactory */
    public static function get($app)
    {
        /** @var WebConsoleFactory $factory */
        $factory = GroutFactory::_getInstance($app, __CLASS__);

        return $factory;
    }

    public function appConfig()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var WebConsoleConfig $tool */
        $tool = $this->app->config->get('Cyantree\WebConsoleModule', 'Cyantree\WebConsoleModule', new WebConsoleConfig());

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    public function appModule()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var WebConsoleModule $tool */
        $tool = $this->app->getModuleByType('Cyantree\WebConsoleModule');

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }
}