<?php
namespace Grout\AppModule;

use Cyantree\Grout\App\ConfigChain;
use Cyantree\Grout\App\Module;
use Cyantree\Grout\DateTime\DateTime;
use DateTimeZone;
use Grout\AppModule\Types\AppConfig;
use Grout\Cyantree\BasicHttpAuthorizationModule\BasicHttpAuthorizationModule;

class AppModule extends Module
{
    /** @var AppConfig */
    public $moduleConfig;

    public function init()
    {
        $chain = new ConfigChain(__NAMESPACE__ . '\Configs\\', 'App');
        $chain->checkConfig($this->config->get('config'));
        $chain->checkConfig('Live');
        $chain->checkMachineName();
        $config = $this->importConfigChain($chain->getChain());

        $this->app->configs->setDefaultConfig($this->id, new AppConfig());
        $this->app->configs->addConfigProvider($config);

        $this->moduleConfig = $this->app->configs->getConfig($this->id);

        DateTime::$local->setTimezone(new DateTimeZone($this->moduleConfig->dateTimezone));
        date_default_timezone_set($this->moduleConfig->dateTimezone);

        $this->_initBaseModules();
    }

    private function _initBaseModules()
    {
        $config = $this->app->getConfig();

        // >> Init logging
        if ($this->moduleConfig->logging) {
            $this->app->importModule('Cyantree\LoggingModule', 'logs/' . $config->internalAccessKey . '/');
        }

        // >> Init error reporting
        if ($this->moduleConfig->errorReporting) {
            $this->app->importModule('Cyantree\ErrorReportingModule', 'errors/' . $config->internalAccessKey . '/');
        }

        // >> Init mail
        if ($this->moduleConfig->mail) {
            $this->app->importModule('Cyantree\MailModule');
        }
    }

    public function initTask($task)
    {
        $section = $task->request->urlParts->get(0);

        // >> Init web console if needed
        if ($this->moduleConfig->webConsole && $section == 'console') {
            if (!$this->app->getConfig()->developmentMode) {
                /** @var BasicHttpAuthorizationModule $module */
                $module = $this->app->importModule('Cyantree\BasicHttpAuthorizationModule', 'console/');
                $module->secureUrl('', 'console_9055', 'PvzeM6Ej3VSdkynf', $this->app->getConfig()->projectTitle);
            }

            $this->app->importModule('Cyantree\WebConsoleModule', 'console/');
        }
    }

    public function destroy()
    {
        $factory = AppFactory::get($this->app);
        if($factory->hasAppTool('appSession')){
            $factory->appSession()->save();
        }

        if($factory->hasAppTool('appDoctrine')){
            $factory->appDoctrine()->close();
        }
    }
}