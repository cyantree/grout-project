<?php
namespace Grout\BootstrapModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\DateTime\DateTime;
use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Tools\AppTools;
use Cyantree\Grout\Tools\ArrayTools;
use DateTimeZone;
use Grout\BootstrapModule\Configs\BootstrapBaseConfig;
use Grout\Cyantree\BasicHttpAuthorizationModule\BasicHttpAuthorizationModule;

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

    public function initTask($task)
    {
        $f = new ArrayFilter();
        foreach ($this->config->needs('mainModules') as $mainModule) {
            $f->setData($mainModule);
            if (!$this->app->moduleImported($f->needs('type'))) {
                $this->app->importModule($f->needs('type'), $f->get('url'), $f->get('config'), $f->get('config'));
            }
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
}