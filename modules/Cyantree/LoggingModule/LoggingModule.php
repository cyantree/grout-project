<?php
namespace Grout\Cyantree\LoggingModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\Logging;
use Cyantree\Grout\Event\Event;
use Grout\Cyantree\LoggingModule\Parsers\LoggingParser;
use Grout\Cyantree\LoggingModule\Types\LoggingConfig;

class LoggingModule extends Module
{
    /** @var Logging */
    public $l;

    public $logDefaultChannel = true;

    /** @var LoggingConfig */
    public $moduleConfig;

    public function init()
    {
        $this->moduleConfig = $this->app->config->get($this->type, $this->id, new LoggingConfig());

        if(!$this->moduleConfig->accessKey){
            $this->moduleConfig->accessKey = $this->app->config->internalAccessKey;
        }

        $this->l = new Logging();
        $this->l->file = $this->app->parseUri($this->moduleConfig->file);
        $this->l->start('START '.$this->app->name, $this->app->timeConstructed);

        $this->app->events->join('log', array($this, 'onLog'));
        $this->app->events->join('log0', array($this, 'onLog'));

        $this->defaultPageType = 'LoggingPage';

        $this->addNamedRoute('get-logs', $this->moduleConfig->accessKey . '/get/');
        $this->addNamedRoute('clear-logs', $this->moduleConfig->accessKey . '/clear/');
    }

    public function logChannel($id)
    {
        $this->app->events->join('log' . $id, array($this, 'onLog'));
    }

    /** @param \Cyantree\Grout\Event\Event $event */
    public function onLog($event)
    {
        if ($event->type == 'log' && !$this->logDefaultChannel) {
            return;
        }

        $this->l->log($event->data);
    }

    public function destroy()
    {
        $this->l->stop('END '.$this->app->name);
    }
}
