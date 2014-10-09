<?php
namespace Grout\AppModule\Configs;

use Cyantree\Grout\App\Config\ConfigProvider;
use Cyantree\Grout\App\GroutAppConfig;
use Grout\AppModule\Types\AppConfig;
use Grout\Cyantree\AclModule\Types\AclAccount;
use Grout\Cyantree\AclModule\Types\AclConfig;
use Grout\Cyantree\AclModule\Types\AclRole;
use Grout\Cyantree\DoctrineModule\Types\DoctrineConfig;
use Grout\Cyantree\ErrorReportingModule\Types\ErrorReportingConfig;
use Grout\Cyantree\LoggingModule\Types\LoggingConfig;
use Grout\Cyantree\MailModule\MailModule;
use Grout\Cyantree\MailModule\Types\MailConfig;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleConfig;

class AppBaseConfig extends ConfigProvider
{
    public function configureGroutApp(GroutAppConfig $config)
    {
        $config->internalAccessKey = '###ACCESS_KEY###';
        $config->projectTitle = 'grout project';
    }

    public function configureAppModule(AppConfig $config)
    {
        $config->errorReporting = true;
    }

    public function configureCyantreeDoctrineModule(DoctrineConfig $config)
    {
        $config->connectionDetails = array(
            'driver' => 'pdo_mysql',
            'dbname' => 'your_database',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => '',
            'collate' => 'utf8_general_ci',
            'charset' => 'utf8'
        );

        $config->entityPaths[] = $this->app->path . 'modules/AppModule/source/Entities';

        // $config->logQueries = true;
    }

    public function configureCyantreeErrorReportingModule(ErrorReportingConfig $config)
    {
        $config->ignoreUploadSizeError = true;
        $config->terminateNoticeError = true;
        $config->convertErrorsToExceptions = true;
        $config->mode = 'show';

        // Uncomment and enter your reporting address
        // $config->email = 'mail@example.org';
    }

    public function configureCyantreeLoggingModule(LoggingConfig $config)
    {

    }

    public function configureCyantreeMailModule(MailConfig $config)
    {
        // Uncomment and enter your default sender address
        // $config->from = 'mail@example.org';

        // Uncomment and enter your debugging address
        // $config->to = 'mail@example.org';
    }

    public function configureCyantreeWebConsoleModule(WebConsoleConfig $config)
    {
        if ($this->app->getConfig()->developmentMode) {
            $config->commandNamespaces[] = 'Grout\AppModule\WebConsoleCommands\Development\\';
        }

        $config->commandNamespaces[] = 'Grout\AppModule\WebConsoleCommands\Live\\';

        $config->defaultCommand = 'Help';
    }

    public function configureCyantreeAclModule(AclConfig $config)
    {
        $config->addRole(new AclRole('root', array('*')), 'guest');
        $config->addAccount(new AclAccount('root', '###ROOT_PASS###', 'root'));
    }
}
