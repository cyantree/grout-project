<?php
namespace Grout\BootstrapModule\Configs;

use Grout\BootstrapModule\Configs\BootstrapBaseConfig;
use Grout\BootstrapModule\GlobalFactory;
use Grout\Cyantree\BucketModule\BucketModule;
use Grout\Cyantree\BucketModule\Types\BucketConfig;
use Grout\Cyantree\DoctrineModule\Types\DoctrineConfig;
use Grout\Cyantree\ErrorReportingModule\Types\ErrorReportingConfig;
use Grout\Cyantree\MailModule\Types\MailConfig;
use Grout\Cyantree\TranslatorModule\Types\TranslatorConfig;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleConfig;
use Grout\ManagedModule\Types\ManagedConfig;

class BootstrapDefaultConfig extends BootstrapBaseConfig
{
    public $projectTitle = 'Grout application';

    // Needed for accessing debug messages and logs, should be 32 chars a-zA-Z0-9
    public $internalAccessKey = 'ReplaceGroutInternalAccessKey';

    // Activate development mode to access internal functionality
    public $developmentMode = false;

    public $dateTimezone = 'Europe/Berlin';

    public $errorReporting = true;

    public $logging = false;

    public $mail = true;

    protected function _create($moduleOrPluginType, $moduleOrPluginId, $templateConfig)
    {
        $config = parent::_create($moduleOrPluginType, $moduleOrPluginId, $templateConfig);

        if($moduleOrPluginType == 'Cyantree\MailModule'){
            /** @var $config MailConfig */
            $config->from = '';

        }elseif($moduleOrPluginType == 'Cyantree\DoctrineModule'){
            /** @var $config DoctrineConfig */
            $config->connectionDetails = array(
                'driver' => 'pdo_mysql',
                'dbname' => '',
                'host' => '127.0.0.1',
                'user' => '',
                'password' => '',
                'collate' => 'utf8_general_ci'
            );
            // $config->entityPaths = array($this->app->parseUri('path://path/to/entities/'));

        }elseif($moduleOrPluginType == 'Cyantree\WebConsoleModule'){
            /** @var $config WebConsoleConfig */
            // $config->commandNamespaces = array('Grout\BootstrapModule\WebConsoleCommands\\');

        }elseif($moduleOrPluginType == 'Cyantree\ErrorReportingModule'){
            /** @var $config ErrorReportingConfig */
            //$config->email = 'mail@example.com';
        }

        return $config;
    }
}