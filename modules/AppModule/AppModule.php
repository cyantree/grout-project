<?php
namespace Grout\AppModule;

use Cyantree\Grout\App\ConfigChain;
use Cyantree\Grout\App\Module;
use Cyantree\Grout\App\Types\ResponseCode;

use Cyantree\Grout\DateTime\DateTime;
use DateTimeZone;
use Grout\AppModule\Types\AppConfig;
use Grout\Cyantree\AclModule\AclModule;
use Grout\Cyantree\AclModule\Types\AclRule;

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

        $this->initModules();
        $this->initRoutes();
    }

    private function initModules()
    {
        // >> Init ACL
        /** @var AclModule $acl */
        $acl = $this->app->importModule('Cyantree\AclModule', null, null, null, 1000);

        if (!$this->app->getConfig()->developmentMode) {
            $acl->secureUrlRecursive('internal/', new AclRule('root'), 'Internal');
        }

        // >> Init logging
        if ($this->moduleConfig->logging) {
            $this->app->importModule('Cyantree\LoggingModule', 'internal/logs/');
        }

        // >> Init error reporting
        if ($this->moduleConfig->errorReporting) {
            $this->app->importModule('Cyantree\ErrorReportingModule', 'internal/errors/');
        }

        // >> Init mail
        if ($this->moduleConfig->mail) {
            $this->app->importModule('Cyantree\MailModule', 'internal/mails/');
        }
    }

    private function initRoutes()
    {
        $this->addRoute('', 'Pages\WelcomePage');

        $this->addErrorRoute(ResponseCode::CODE_403, 'Pages\TemplatePage', array('template' => '403.html'));
        $this->addErrorRoute(ResponseCode::CODE_404, 'Pages\TemplatePage', array('template' => '404.html'));
        $this->addErrorRoute(ResponseCode::CODE_500, 'Pages\TemplatePage', array('template' => '500.html'));
    }

    public function initTask($task)
    {
        // >> Init web console if needed
        if ($this->moduleConfig->webConsole && preg_match('!^internal/console/!', $task->request->url)) {
            $this->app->importModule('Cyantree\WebConsoleModule', 'internal/console/');
        }
    }
}
