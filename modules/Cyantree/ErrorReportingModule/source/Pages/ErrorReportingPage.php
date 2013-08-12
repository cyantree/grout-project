<?php
namespace Grout\Cyantree\ErrorReportingModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Types\ContentType;
use Cyantree\Grout\App\Types\ResponseCode;
use Exception;
use Grout\Cyantree\ErrorReportingModule\ErrorReportingModule;

class ErrorReportingPage extends Page
{
    public function beforeParsing()
    {
        $this->task->response->contentType = ContentType::TYPE_PLAIN_UTF8;
    }

    public function parseTask()
    {

        /** @var $m ErrorReportingModule */
        $m = $this->task->module;

        if ($this->task->route->id == 'trigger-error') {
            throw new Exception("An test error has been triggered.", E_ERROR);
        } else if ($this->task->route->id == 'get-errors') {
            if (!$m->moduleConfig->file || !is_file($m->moduleConfig->file) || !filesize($m->moduleConfig->file)) {
                $c = 'No errors available';
            } else {
                $c = file_get_contents($m->moduleConfig->file);
            }

            $this->task->response->postContent($c);
        } else if ($this->task->route->id == 'clear-errors') {
            if ($m->moduleConfig->file) {
                file_put_contents($m->moduleConfig->file, '');
            }

            $this->task->response->postContent('All errors have been cleared.');
        }
    }

    public function parseError($error, $data = null)
    {
        $this->task->response->postContent('An unknown error has occurred.', ContentType::TYPE_PLAIN_UTF8);
        $this->task->response->code = ResponseCode::CODE_500;
    }
}