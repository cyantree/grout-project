<?php
namespace Grout\BootstrapModule\Configs;

use Cyantree\Grout\App\GroutAppConfig;

class BootstrapBaseConfig extends GroutAppConfig
{
    public $dateTimezone = 'Europe/Berlin';

    public $errorReporting = false;

    public $logging = false;

    public $mail = false;
}