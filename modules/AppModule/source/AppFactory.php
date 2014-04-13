<?php
namespace Grout\AppModule;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Generators\Template\TemplateGenerator;
use Cyantree\Grout\App\GroutFactory;
use Cyantree\Grout\Bucket\Bucket;
use Cyantree\Grout\Bucket\FileBucket;
use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Session\BucketSession;
use Cyantree\Grout\Task\TaskManager;
use Cyantree\Grout\Ui\Ui;
use Doctrine\ORM\EntityManager;
use Grout\AppModule\Helpers\AppQuick;
use Grout\AppModule\Types\AppConfig;
use Grout\AppModule\Types\AppTemplateContext;
use Grout\Cyantree\DoctrineModule\DoctrineModule;

class AppFactory extends GroutFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @return AppFactory */
    public static function get(App $app = null)
    {
        return GroutFactory::_getInstance($app, __CLASS__, 'AppModule', 'AppModule');
    }

    /** @var AppConfig $tool */
    public function appConfig()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var AppConfig $tool */
        $tool = $this->app->configs->getConfig('AppModule');

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return BucketSession */
    public function appSession()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new BucketSession();
        $tool->bucketBase = $this->appBuckets();
        $tool->name = 'grout_' . substr(md5($this->app->getConfig()->internalAccessKey), 0, 8);

        $tool->load();

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return ArrayFilter */
    public function appSessionData()
    {
        $session = $this->appSession();

        if ($session->data === null) {
            $session->data = new ArrayFilter();
        }

        return $session->data;
    }

    /** @return Bucket */
    public function appBuckets()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new FileBucket();
        $tool->directory = $this->app->dataStorage->createStorage('App\Buckets');

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
        $tool->directory = $this->app->dataStorage->createStorage('App\Tasks');
        $tool->keepFailedTasks = true;

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

    /** @return TemplateGenerator */
    public function appTemplates()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new TemplateGenerator();
        $tool->app = $this->app;
        $tool->setTemplateContext(new AppTemplateContext());

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return AppQuick */
    public function appQuick()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new AppQuick($this->app);
        $tool->publicAssetUrl = $this->app->publicUrl . $this->app->getConfig()->assetUrl;

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }

    /** @return Ui */
    public function appUi()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        $tool = new Ui();

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }
}