<?php
namespace Grout\Cyantree\ServiceModule;

use Cyantree\Grout\App\GroutFactory;
use Grout\BootstrapModule\GlobalFactory;
use Grout\Cyantree\ServiceModule\Types\ServiceConfig;

class ServiceFactory extends GlobalFactory
{
    /** @return ServiceFactory */
    public static function get($app)
    {
        /** @var ServiceFactory $factory */
        $factory = GroutFactory::_getInstance($app, __CLASS__);

        return $factory;
    }

    public function appConfig()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var ServiceConfig $tool */
        $tool = $this->app->config->get('Cyantree\ServiceModule', 'Cyantree\ServiceModule', new ServiceConfig());

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    public function appModule()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var ServiceModule $tool */
        $tool = $this->app->getModuleByType('Cyantree\ServiceModule');

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }
}