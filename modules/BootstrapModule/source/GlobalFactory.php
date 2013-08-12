<?php
namespace Grout\BootstrapModule;

use Cyantree\Grout\App\GroutFactory;
use Cyantree\Grout\Bucket\Bucket;
use Cyantree\Grout\Session\BucketSession;
use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Logging;
use Cyantree\Grout\Session\Session;
use Cyantree\Grout\Task\TaskManager;
use Cyantree\Grout\Ui\Ui;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Grout\Cyantree\BucketModule\BucketModule;
use Grout\Cyantree\DoctrineModule\DoctrineModule;

class GlobalFactory extends GroutFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @return GlobalFactory */
    public static function get($app)
    {
        return GroutFactory::_getInstance($app, __CLASS__);
    }

    /** @return \Cyantree\Grout\Session\BucketSession */
    public function appSession()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new BucketSession();
        $tool->bucketBase = $this->appBuckets();
        $tool->name = 'Grout';

        $tool->load();

        if ($tool->isNew) {
            $tool->data = new ArrayFilter();
        }

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return ArrayFilter */
    public function appSessionData()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = $this->appSession()->data;

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return Bucket */
    public function appBuckets()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var BucketModule $module */
        $module = $this->app->importModule('Cyantree\BucketModule');
        $tool = $module->bucket;

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return TaskManager */
    public function appTasks()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new TaskManager();
        $tool->directory = $this->app->parseUri('data://tasks/');

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return EntityManager */
    public function appDoctrine()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var DoctrineModule $module */
        $module = $this->app->importModule('Cyantree\DoctrineModule');
        $tool = $module->getEntityManager();

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }
}