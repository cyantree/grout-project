<?php
namespace Grout\Cyantree\WebConsoleModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Types\ResponseCode;
use Cyantree\Grout\ErrorWrapper\PhpErrorException;
use Cyantree\Grout\ErrorWrapper\PhpWarningException;
use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Tools\ServerTools;
use Grout\BootstrapModule\GlobalFactory;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleCommand;
use Grout\Cyantree\WebConsoleModule\WebConsoleFactory;

class WebConsolePage extends Page
{
    public function parseTask()
    {
        $request= $this->task->request->post->get('command');
        $result = $this->_processRequest($request);

        $this->setResult(GlobalFactory::get($this->app)->appTemplates()->load('Cyantree\WebConsoleModule::console.html', array('command' => $request, 'result' => $result)));
    }

    protected function _processRequest($request){
        if($request == ''){
            return '';
        }

        $factory = WebConsoleFactory::get($this->app);
        $config = $factory->appConfig();

        $args = ServerTools::parseCommandlineString($request);

        $command = $args[0];
        $result = '';

        $get = array();
        if(count($args) > 1){
            $args = array_splice($args, 1);
            foreach($args as $arg){
                if(substr($arg, 0, 2) == '--'){
                    $get[substr($arg, 2)] = true;
                }elseif(substr($arg, 0, 1) == '-'){
                    $s = explode('=', $arg, 2);

                    $get[substr($s[0], 1)] = $s[1];
                }else{
                    $get[] = $arg;
                }
            }
        }

        try{
            $command = str_replace('/', '\\', $command);

            if(!preg_match('!^[a-zA-Z0-9_/]+$!', $command)){
                $result = 'Command not found';
            }else{
                $found = false;

                $className = null;
                foreach($config->commandNamespaces as $commandNamespace){
                    $className = $commandNamespace.$command.'Command';

                    if(class_exists($className)){
                        $found = true;
                        break;
                    }
                }

                if($found){
                    /** @var WebConsoleCommand $c */
                    $c = new $className();
                    $c->task = $this->task;
                    $c->app = $this->app;
                    $c->args = new ArrayFilter($get);
                    $c->execute();
                    $result = $c->result;
                }else{
                    $result = 'Command not found';
                }
            }
        }catch(PhpWarningException $e){
            $this->task->app->events->trigger('logException', $e);
            $this->parseError(ResponseCode::CODE_500);
        }catch(PhpErrorException $e){
            $this->task->app->events->trigger('logException', $e);
            $this->parseError(ResponseCode::CODE_500);
        }

        return $result;
    }
}