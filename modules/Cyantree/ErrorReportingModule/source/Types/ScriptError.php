<?php
namespace Grout\Cyantree\ErrorReportingModule\Types;

class ScriptError
{
    public $terminate;
    public $suppress;

    public $code;
    public $type;
    public $message;
    public $line;
    public $file;
    public $context;

    public $stackTrace;

    public function __construct($type = null, $message = null)
    {
        $this->type = $type;
        $this->message = $message;
    }
}
