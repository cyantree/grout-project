<?php
namespace Grout\BootstrapModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\AutoLoader;
use Cyantree\Grout\Database\Database;
use Cyantree\Grout\Database\PdoConnection;
use Cyantree\Grout\DateTime\DateTime;
use Cyantree\Grout\Tools\AppTools;
use Cyantree\Grout\Tools\ArrayTools;
use DateTimeZone;
use Grout\BootstrapModule\Configs\BootstrapBaseConfig;

class BootstrapModule extends Module
{
    /** @var  BootstrapBaseConfig */
    public $moduleConfig;
    
    public function init()
    {

        if($config = $this->config->get('config')){
            $config = $this->namespace.'Configs\\'.$config;
            $this->moduleConfig = new $config();
        }else{
            $this->moduleConfig = $this->importConfigChain(AppTools::createConfigChain('Live', 'Default', __NAMESPACE__.'\Configs', true, true, 'Bootstrap'));
        }

        $this->app->setConfig($this->moduleConfig);

        DateTime::$local->setTimezone(new DateTimeZone($this->moduleConfig->dateTimezone));
        date_default_timezone_set($this->moduleConfig->dateTimezone);

        $this->_initBaseModules();

        $this->_initGlobals();

        $this->_startup();
    }

    private function _initBaseModules()
    {
        // >> Init logging
        if ($this->moduleConfig->logging) {
            $this->app->importModule('Cyantree\LoggingModule', 'logs/');
        }

        // >> Init error reporting
        if ($this->moduleConfig->errorReporting) {
            $this->app->importModule('Cyantree\ErrorReportingModule', 'errors/');
        }

        // >> Init mail
        if ($this->moduleConfig->mail) {
            $this->app->importModule('Cyantree\MailModule');
        }
    }

    private function _initGlobals()
    {
    }

    public function initTask($task)
    {

        if($this->moduleConfig->developmentMode && $task->request->urlParts->get(0) == 'console'){
            $this->app->importModule('Cyantree\WebConsoleModule', 'console/');

        }elseif($task->request->urlParts->get(0) == 'admin' && !$this->app->moduleImported('ManagedModule')){

            $this->app->importModule('ManagedModule', 'admin/');

        }elseif(!$this->app->moduleImported($this->config->needs('mainModule'))){

            $this->app->importModule($this->config->needs('mainModule'));
        }

        parent::initTask($task);
    }

    public function destroy()
    {
        $factory = GlobalFactory::get($this->app);
        if($factory->hasAppTool('appSession')){
            $factory->appSession()->save();
        }

        if($factory->hasAppTool('appDoctrine')){
            $factory->appDoctrine()->close();
        }
    }


    private function _startup()
    {
        // >> Register development modules

    }
}