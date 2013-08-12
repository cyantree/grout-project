<?php
namespace Grout\Cyantree\ConsoleModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Types\ResponseCode;
use Cyantree\Grout\Database\Database;
use Cyantree\Grout\ErrorWrapper\PhpErrorException;
use Cyantree\Grout\ErrorWrapper\PhpWarningException;
use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Tools\ArrayTools;
use Cyantree\Grout\Tools\ServerTools;
use Grout\Cyantree\ConsoleModule\ConsoleModule;
use Grout\Cyantree\ConsoleModule\Types\ConsoleCommand;

class ConsoleCommandPage extends Page
{
    public function parseTask()
    {
        $factory = WebConsoleFactory::get($this->app);
        $config = $factory->appConfig();

        try{
            $command = str_replace('/', '\\', $this->task->vars->get('command'));

            if(!preg_match('!^[a-zA-Z0-9_/]+$!', $command)){
                $this->parseError(ResponseCode::CODE_404);
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
                    /** @var ConsoleCommand $c */
                    $c = new $className();
                    $c->task = $this->task;
                    $c->app = $this->app;
                    $c->args = new ArrayFilter($this->task->request->get->getData());
                    $c->execute();
                }else{
                    $this->parseError(ResponseCode::CODE_404);
                }
            }
        }catch(PhpWarningException $e){
            $this->task->app->events->trigger('logException', $e);
            $this->parseError(ResponseCode::CODE_500);
        }catch(PhpErrorException $e){
            $this->task->app->events->trigger('logException', $e);
            $this->parseError(ResponseCode::CODE_500);
        }
    }

    public function parseError($code, $data = null)
    {
        if($code == ResponseCode::CODE_404){
            echo 'The command wasn\'t found.'.chr(10);
        }else{
            echo 'An unknown error occurred.'.chr(10);
        }
    }
}