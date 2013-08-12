<?php
namespace Grout\Cyantree\BasicHttpAuthorizationModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Types\ContentType;
use Cyantree\Grout\App\Types\ResponseCode;
use Grout\Cyantree\BasicHttpAuthorizationModule\BasicHttpAuthorizationModule;

class SecuredPage extends Page
{
    public function parseTask()
    {
        /** @var BasicHttpAuthorizationModule $module */
        $module = $this->task->module;

        $name = $this->task->route->data->get('name', $module->moduleConfig->realm);

        $this->task->response->code = ResponseCode::CODE_401;
        $this->task->response->postContent('You are not allowed to access this page.', ContentType::TYPE_PLAIN_UTF8, true);
        $this->task->response->headers['WWW-Authenticate'] = 'Basic realm="' . $name . '"';
    }
}