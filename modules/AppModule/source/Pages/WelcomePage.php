<?php
namespace Grout\AppModule\Pages;

use Cyantree\Grout\App\Page;

class WelcomePage extends TemplatePage
{
    public function parseTask()
    {
        $this->setTemplateResult('welcome.html', array('name' => $this->task->request->post->get('name', 'Anonymous')));
    }
}