<?php
namespace Grout\AppModule\Pages;

class WelcomePage extends TemplatePage
{
    public function parseTask()
    {
        $this->setTemplateResult('welcome.html', array('name' => $this->task->request->post->get('name', 'Anonymous')));
    }
}