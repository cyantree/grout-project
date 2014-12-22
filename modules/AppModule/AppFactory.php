<?php
namespace Grout\AppModule;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Generators\Template\TemplateGenerator;
use Cyantree\Grout\App\GroutFactory;
use Cyantree\Grout\Bucket\Bucket;
use Cyantree\Grout\Bucket\FileBucket;
use Cyantree\Grout\Event\Event;
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
        return GroutFactory::getFactory($app, __CLASS__, 'AppModule', 'AppModule');
    }

    /** @return AppConfig */
    public function config()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            /** @var AppConfig $tool */
            $tool = $this->app->configs->getConfig('AppModule');

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return BucketSession */
    public function session()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = new BucketSession();
            $tool->bucketBase = $this->buckets();
            $tool->name = 'grout_' . substr(md5($this->app->getConfig()->internalAccessKey), 0, 8);

            $tool->load();

            $this->app->events->join('destroy', function (Event $event, BucketSession $session) {
                      $session->save();
            }, $tool);

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return ArrayFilter */
    public function sessionData()
    {
        $session = $this->session();

        if ($session->data === null) {
            $session->data = new ArrayFilter();
        }

        return $session->data;
    }

    /** @return Bucket */
    public function buckets()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = new FileBucket();
            $tool->directory = $this->app->dataStorage->createStorage('App\Buckets');

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return TaskManager */
    public function tasks()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = new TaskManager();
            $tool->directory = $this->app->dataStorage->createStorage('App\Tasks');
            $tool->keepFailedTasks = true;

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return DoctrineModule */
    public function doctrineModule()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            /** @var DoctrineModule $tool */
            $tool = $this->app->importModule('Cyantree\DoctrineModule');

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return EntityManager */
    public function doctrine()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = $this->doctrineModule()->getEntityManager();

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return TemplateGenerator */
    public function templates()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = new TemplateGenerator();
            $tool->app = $this->app;
            $tool->setTemplateContext(new AppTemplateContext());

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return AppQuick */
    public function quick()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = new AppQuick($this->app);

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return Ui */
    public function ui()
    {
        if (!($tool = $this->retrieveTool(__FUNCTION__))) {
            $tool = new Ui();

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    public function log($data)
    {
        $this->app->events->trigger('log', $data);
    }

    public function logException($exception)
    {
        $this->app->events->trigger('logException', $exception);
    }
}