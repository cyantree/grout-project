<?php
namespace Grout\BootstrapModule\Pages;

use Cyantree\Grout\App\Page;
use Grout\BootstrapModule\GlobalFactory;

class TemplatePage extends Page
{
    public function parseTask()
    {
        $this->setResult(
            GlobalFactory::get($this->app)->appTemplates()->load($this->task->vars->get('template'),
                null,
                $this->task->vars->get('baseTemplate', 'base.html')
            )->content,
            $this->task->vars->get('contentType'),
            $this->task->vars->get('responseCode'));
    }
}