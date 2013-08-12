<?php
namespace Grout\Cyantree\MailModule\Types;

use Grout\Cyantree\MailModule\MailModule;

class MailConfig
{
    public $mode = MailModule::MODE_SEND;
    public $from;
    public $to;
    public $directory = 'data://mails/';
}