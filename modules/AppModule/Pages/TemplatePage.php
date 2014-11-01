<?php
namespace Grout\AppModule\Pages;

use Cyantree\Grout\Filter\ArrayFilter;
use Grout\AppModule\Types\AppPage;

class TemplatePage extends AppPage
{
    public function parseTask()
    {
        $this->setTemplateResult($this->task->vars->get('template'), $this->task->vars->get('templateData'), array(
                'baseTemplate' => $this->task->vars->get('baseTemplate'),
                'contentType' => $this->task->vars->get('contentType'),
                'responseCode' => $this->task->vars->get('responseCode')
            ));
    }

    public function setTemplateResult($template, $templateData = null, $settings = null)
    {
        $settings = new ArrayFilter($settings);

        $content = $this->factory()->templates()->load($template, $templateData)->content;

        $baseTemplate = $settings->get('baseTemplate');
        if ($baseTemplate === null) {
            $baseTemplate = 'AppModule::base.html';
        }

        if ($baseTemplate !== null && $baseTemplate !== false) {
            $content = $this->factory()->templates()->load($baseTemplate, array('content' => $content))->content;
        }

        $this->setResult($content, $settings->get('contentType'), $settings->get('responseCode'));
    }
}
