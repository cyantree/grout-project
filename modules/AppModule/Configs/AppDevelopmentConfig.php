<?php
namespace Grout\AppModule\Configs;

use Cyantree\Grout\App\GroutAppConfig;
use Grout\AppModule\Types\AppConfig;

class AppDevelopmentConfig extends AppBaseConfig
{
    public function configureGroutApp(GroutAppConfig $config)
    {
        parent::configureGroutApp($config);

        $config->developmentMode = true;
    }

    public function configureAppModule(AppConfig $config)
    {
        parent::configureAppModule($config);

        $config->logging = true;
    }
}
