<?php
namespace Grout\AppModule\Configs;

use Grout\Cyantree\DoctrineModule\ConnectionDetails\PdoMySqlConnectionDetails;
use Grout\Cyantree\DoctrineModule\Types\DoctrineConfig;

class AppLiveConfig extends AppDevelopmentConfig
{
    public function configureCyantreeDoctrineModule(DoctrineConfig $config)
    {
        parent::configureCyantreeDoctrineModule($config);

        /** @var PdoMySqlConnectionDetails $connectionDetails */
        $connectionDetails = $config->connectionDetails;

        $connectionDetails->username = 'root';
        $connectionDetails->password = '';
        $connectionDetails->database = '';
    }
}
