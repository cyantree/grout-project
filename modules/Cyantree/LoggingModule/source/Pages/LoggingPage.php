<?php
namespace Grout\Cyantree\LoggingModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Types\ContentType;
use Grout\Cyantree\LoggingModule\LoggingModule;

class LoggingPage extends Page
{
    public function beforeParsing()
    {
        $this->task->response->contentType = ContentType::TYPE_PLAIN_UTF8;
    }

    public function parseTask()
    {
        /** @var $m LoggingModule */
        $m = $this->task->module;

        if ($this->task->route->id == 'get-logs') {
            if (!is_file($m->l->file) || !filesize($m->l->file)) {
                $c = 'No logs available.';
            } else {
                $c = file_get_contents($m->l->file);
            }

            $this->task->response->postContent($c);
        } else if ($this->task->route->id == 'clear-logs') {
            file_put_contents($m->l->file, '');

            $this->task->response->postContent('All logs have been cleared.');
        }
    }

    public function parseError($error, $data = null)
    {
        $this->task->response->code = $error;
        $this->task->response->postContent('An unknown error has occurred.');
    }
}