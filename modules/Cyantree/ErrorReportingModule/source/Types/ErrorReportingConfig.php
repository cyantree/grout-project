<?php
namespace Grout\Cyantree\ErrorReportingModule\Types;

use Grout\Cyantree\ErrorReportingModule\ErrorReportingModule;

class ErrorReportingConfig
{
    public $mode = ErrorReportingModule::MODE_LOG;

    public $file = 'data://errors.txt';
    public $fileMaxSize = 1000000;
    public $fileTruncateSize = 20000;

    public $email = null;

    public $emailSender = null;

    public $emailAllErrors = false;

    public $accessKey = null;
}