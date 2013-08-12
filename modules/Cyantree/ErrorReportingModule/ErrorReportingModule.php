<?php
namespace Grout\Cyantree\ErrorReportingModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\DateTime\DateTime;
use Cyantree\Grout\ErrorWrapper\ErrorWrapper;
use Cyantree\Grout\Mail\Mail;
use Cyantree\Grout\Event\Event;
use Exception;
use Grout\Cyantree\ErrorReportingModule\Pages\ErrorReportingPage;
use Grout\Cyantree\ErrorReportingModule\Types\ErrorReportingConfig;
use Grout\Cyantree\ErrorReportingModule\Types\ScriptError;

class ErrorReportingModule extends Module
{
    const MODE_LOG = 'log';
    const MODE_SHOW = 'show';
    const MODE_AUTO = 'auto';

    private static $_started;

    public $suppressErrors = false;

    public $errorCallback;
    public $terminateCallback;

    private $_reportedErrors = false;

    private $_previousErrorReporting;
    private $_previousDisplayErrors;

    /** @var ErrorReportingConfig */
    public $moduleConfig;

    public function init()
    {
        $this->moduleConfig = $this->app->config->get($this->type, $this->id, new ErrorReportingConfig());

        if(!$this->moduleConfig->accessKey){
            $this->moduleConfig->accessKey = $this->app->config->internalAccessKey;
        }

        if ($this->moduleConfig->mode != ErrorReportingModule::MODE_AUTO) {
            $this->app->events->join('stopErrorReporting', array($this, 'onChangeErrorReporting'));
            $this->app->events->join('startErrorReporting', array($this, 'onChangeErrorReporting'));
            $this->app->events->join('emergencyShutdown', array($this, 'onEmergencyShutdown'));

            $this->defaultPageType = 'ErrorReportingPage';

            $this->addNamedRoute('get-errors', $this->moduleConfig->accessKey . '/get/');
            $this->addNamedRoute('clear-errors', $this->moduleConfig->accessKey . '/clear/');
            $this->addNamedRoute('trigger-error', $this->moduleConfig->accessKey . '/trigger/');

            $this->app->events->join('logException', array($this,'onLogException'));

            $this->_startUpReporting();
        }
    }

    public function onEmergencyShutdown($e)
    {
        $this->suppressErrors = true;
    }

    /** @param \Cyantree\Grout\Event\Event $e */
    public function onChangeErrorReporting($e)
    {
        $this->suppressErrors = $e->type == 'stopErrorReporting';
    }

//    public function _onShutdown()
//    {
//        if(!self::$_started){
//            return;
//        }
//
//        $error = error_get_last();
//
//        if ($error['type']) {
//            $e = new ScriptError($error['type'], $error['message']);
//            $e->file = $error['file'];
//            $e->line = $error['line'];
//
//            $this->processError($e);
//        }
//    }

    /** @param $e Exception */
    public function _onException($e)
    {
        $se = new ScriptError($e->getCode(), $e->getMessage());
        $se->type = get_class($e);
        $se->file = $e->getFile();
        $se->line = $e->getLine();
        $se->stackTrace = $e->getTraceAsString();
        $se->terminate = true;

        $this->processError($se);
    }

//    public function _onError($no, $str, $file = null, $line = null, $context = null)
//    {
//        $se = new ScriptError($no, $str);
//        $se->file = $file;
//        $se->line = $line;
//        $se->context = $context;
//
//        if (PHP_VERSION >= '5.3.6') {
//            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
//        } else {
//            $backtrace = debug_backtrace(false);
//        }
//
//        $ic = count($backtrace);
//        for ($i = 1; $i < $ic; $i++) {
//            $im = $backtrace[$i];
//            if ($im && isset($im['line'])) {
//                $se->stackTrace .= '#' . $i . ' ' . $im['file'] . ' (' . $im['line'] . ')' . chr(10);
//            }
//        }
//
//        $this->reportError($se);
//    }

    public function reportError(ScriptError $e){
        if ($this->app->currentTask) {
            $url = $this->app->url . $this->app->currentTask->request->url;
        } else {
            $url = $this->app->url . '[UNKNOWN-URL]';
        }
        $errorSignature = md5($e->type.$e->message.$e->file.$e->line.$e->stackTrace);
        $data =
              'URL: ' . $url . chr(10) .
              'Date: ' . DateTime::$default->toLongDateTimeString(time(), true) . chr(10) . chr(10) .

              'Signature: '.$errorSignature.chr(10) .
              'Type: ' . $e->type . chr(10) .
              'Message: ' . $e->message . chr(10);

        if ($e->file) {
            $data .= 'File: ' . $e->file . chr(10);
        }
        if ($e->line) {
            $data .= 'Line: ' . $e->line . chr(10);
        }

        $data .= chr(10) . $e->stackTrace;

        $sendErrorMail = $this->moduleConfig->email && !$this->_reportedErrors
            && ($this->moduleConfig->emailAllErrors || !$this->moduleConfig->file || !file_exists($this->moduleConfig->file) || !filesize($this->moduleConfig->file));

        $errorData = $data . chr(10) . '--' . chr(10) . chr(10);

        if ($this->moduleConfig->file) {
            if ($this->moduleConfig->fileMaxSize && file_exists($this->moduleConfig->file) && filesize($this->moduleConfig->file) > $this->moduleConfig->fileMaxSize) {
                file_put_contents($this->moduleConfig->fileMaxSize, substr(file_get_contents($this->moduleConfig->file), $this->moduleConfig->fileTruncateSize) . $errorData);
            } else {
                file_put_contents($this->moduleConfig->file, $errorData, FILE_APPEND);
            }
        }

        if ($sendErrorMail) {
            $subject = '[Error] '. $this->app->name .' (@'.$errorSignature.')';

            if ($this->moduleConfig->emailAllErrors) {
                $text = 'Some errors occurred on your website:' . chr(10) .
                      $this->getRouteUrl('get-errors', null, true) . chr(10) .
                      $this->getRouteUrl('clear-errors', null, true).chr(10).chr(10).
                      $data;

            } else {
                $text = 'Some errors occurred on your website:' . chr(10) .
                      $this->getRouteUrl('get-errors', null, true) . chr(10) . chr(10) .
                      'You won\'t receive any other mails until you clean up the error log:' . chr(10) .
                      $this->getRouteUrl('clear-errors', null, true);
            }

            $m = new Mail($this->moduleConfig->emailAllErrors, $subject, $text, null, $this->moduleConfig->emailSender);
            $this->app->events->trigger('mail', $m);
        }

        $this->_reportedErrors = true;
    }

    /** @param $e ScriptError */
    public function processError($e)
    {
        // PHP changes working directory, so change it back
        chdir($this->app->path);

//        if (!$e->terminate) {
//            $e->terminate = $e->type != E_NOTICE && $e->type != E_USER_NOTICE && $e->type != E_STRICT && $e->type != E_DEPRECATED && $e->type != E_USER_DEPRECATED;
//        }

        if (!$e->suppress) {
            $e->suppress = $this->suppressErrors;
        }

//        if ($e->suppress && $e->terminate && in_array($e->type, array(E_COMPILE_WARNING, E_CORE_WARNING, E_USER_WARNING, E_WARNING))) {
//            $e->terminate = false;
//        }

        if ($this->errorCallback) {
            call_user_func($this->errorCallback, $e);
        }

        // Error should be reported
        if (!$e->suppress) {
            $this->reportError($e);
        }

        if ($e->terminate) {
            while (ob_get_level()) {
                ob_end_clean();
            }

            if ($this->terminateCallback) {
                call_user_func($this->terminateCallback, $e);
            }

            if ($this->app->currentTask && !$this->app->currentTask->page) {
                $this->app->currentTask->setPage(new ErrorReportingPage());
            }

            $this->app->emergencyShutdown($e);

//            exit;
        }
    }

    public function getErrorCache()
    {
        if (!$this->moduleConfig->file || !file_exists($this->moduleConfig->file)) {
            return null;
        }

        return file_get_contents($this->moduleConfig->file);
    }

    public function clearErrorCache()
    {
        if (!$this->moduleConfig->file || !file_exists($this->moduleConfig->file)) {
            return;
        }

        file_put_contents($this->moduleConfig->file, '');
    }

    private function _startUpReporting()
    {
        $mode = $this->moduleConfig->mode;

        $this->moduleConfig->file = $this->app->parseUri($this->moduleConfig->file);

        if ($mode === ErrorReportingModule::MODE_LOG) {
            $this->_catchErrors();
        } else if ($mode === ErrorReportingModule::MODE_SHOW) {
            $this->_showErrors();
        }
    }

    private function _showErrors()
    {
        $this->_previousDisplayErrors = ini_set('display_errors', true);
        $this->_previousErrorReporting = error_reporting(E_ALL);
    }

    private function _catchErrors()
    {
        if (self::$_started) {
            return;
        }

        self::$_started = true;

        // Log previous error
        $error = error_get_last();
        if ($error['type']) {
            $e = new ScriptError($error['type'], $error['message']);
            $e->file = $error['file'];
            $e->line = $error['line'];
            $e->terminate = false;

            $this->processError($e);
        }

//        set_error_handler(array($this, '_onError'));



        set_exception_handler(array($this, '_onException'));

        $this->_previousErrorReporting = error_reporting(0);
        $this->_previousDisplayErrors = ini_set('display_errors', false);

        ErrorWrapper::register();
    }

    public function destroy()
    {
        if(self::$_started){
//            restore_error_handler();
            restore_exception_handler();

//            register_shutdown_function(array($this, '_onShutdown'));

            error_reporting($this->_previousErrorReporting);
            ini_set('display_errors', $this->_previousDisplayErrors);

            ErrorWrapper::unregister();
        }
    }

    public function onLogException(Event $e){
        /** @var Exception $exception */
        $exception =  $e->data;

        $se = new ScriptError($exception->getCode(), $exception->getMessage());
        $se->file = $exception->getFile();
        $se->line = $exception->getLine();
        $se->stackTrace = $exception->getTraceAsString();

        $this->reportError($se);
    }
}

