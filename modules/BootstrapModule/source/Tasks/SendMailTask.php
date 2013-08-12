<?php
namespace Grout\BootstrapModule\Tasks;

use Cyantree\Grout\Mail\Mail;
use Cyantree\Grout\Task\Task;

class SendMailTask extends \Cyantree\Grout\Task\Task
{
    public $to;
    public $from;
    public $subject;
    public $text;
    public $html;

    public function execute()
    {
        $m = new Mail($this->to, $this->subject, $this->text, $this->html, $this->from);
        $this->manager->app->events->trigger('mail', $m);

        $this->_finish();
    }
}