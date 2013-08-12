<?php
namespace Grout\Cyantree\DoctrineModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\Logging;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Persistence\Mapping\Driver\PHPDriver;
use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Grout\Cyantree\DoctrineModule\Types\DoctrineConfig;

class DoctrineModule extends Module
{
    /** @var DoctrineConfig */
    public $moduleConfig;

    /** @var EntityManager */
    private $_entityManager;

    public function init()
    {
        $this->moduleConfig = $this->app->config->get($this->type, $this->id, new DoctrineConfig());
    }

    /** @return EntityManager */
    public function getEntityManager()
    {
        if(!$this->_entityManager){
            $c = $this->app->config;

            $cache = new FilesystemCache($this->app->parseUri('data://doctrine/cache/'));
            $proxies = $this->app->parseUri('data://doctrine/proxies/');
            $config = Setup::createConfiguration($c->developmentMode, $proxies, $cache);
            $driver = new StaticPHPDriver($this->moduleConfig->entityPaths);
            $config->setMetadataDriverImpl($driver);

            if($c->developmentMode && $this->moduleConfig->logQueries){
                $config->setSQLLogger(new DoctrineLogger($this->app));
            }

            $this->_entityManager = EntityManager::create($this->moduleConfig->connectionDetails, $config);
        }

        return $this->_entityManager;
    }
}