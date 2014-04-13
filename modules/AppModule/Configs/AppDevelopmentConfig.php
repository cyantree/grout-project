<?php
namespace Grout\AppModule\Configs;

use Cyantree\Grout\App\GroutAppConfig;
use Grout\Cyantree\ManagedModule\Types\ManagedConfig;

class AppDevelopmentConfig extends AppBaseConfig
{
    public function configureGroutApp(GroutAppConfig $config)
    {
        parent::configureGroutApp($config);

        $config->developmentMode = true;
    }
}